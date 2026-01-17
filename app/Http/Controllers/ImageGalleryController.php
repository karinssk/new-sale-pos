<?php

namespace App\Http\Controllers;

use App\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageGalleryController extends Controller
{
    /**
     * Get images with filtering and pagination
     */
    public function getImages(Request $request)
    {
        try {
            $business_id = $request->session()->get('user.business_id');
            
            // Debug information
            $debug = $request->get('debug', false);
            if ($debug) {
                $debug_info = [
                    'session_business_id' => $business_id,
                    'all_session_data' => $request->session()->all(),
                    'total_media_records' => Media::count(),
                    'business_media_records' => Media::where('business_id', $business_id)->count(),
                    'all_businesses' => \App\Business::pluck('name', 'id')->toArray()
                ];
                
                return response()->json([
                    'success' => true,
                    'debug_info' => $debug_info,
                    'message' => 'Debug information retrieved'
                ]);
            }
            
            $per_page = $request->get('per_page', 30);
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            $date_from = $request->get('date_from');
            $date_to = $request->get('date_to');
            $sort = $request->get('sort', 'newest'); // newest, oldest, name
            
            if (!$business_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No business ID found in session. Please log in again.',
                    'debug_info' => [
                        'session_data' => $request->session()->all()
                    ]
                ], 400);
            }
            
            $query = Media::where('business_id', $business_id)
                ->whereIn('model_media_type', ['product_image', null])
                ->where(function($q) {
                    $q->where('file_name', 'like', '%.jpg')
                      ->orWhere('file_name', 'like', '%.jpeg')
                      ->orWhere('file_name', 'like', '%.png')
                      ->orWhere('file_name', 'like', '%.gif')
                      ->orWhere('file_name', 'like', '%.webp');
                });
            
            // Apply search filter
            if (!empty($search)) {
                $query->where('file_name', 'like', '%' . $search . '%');
            }
            
            // Apply date filters
            if (!empty($date_from)) {
                $query->whereDate('created_at', '>=', $date_from);
            }
            
            if (!empty($date_to)) {
                $query->whereDate('created_at', '<=', $date_to);
            }
            
            // Apply sorting
            switch ($sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name':
                    $query->orderBy('file_name', 'asc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
            
            $media = $query->paginate($per_page);
            
            // Group images by date
            $grouped_images = [];
            foreach ($media as $image) {
                $date = $image->created_at->format('d/m/Y');
                if (!isset($grouped_images[$date])) {
                    $grouped_images[$date] = [];
                }
                $grouped_images[$date][] = [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    'display_name' => $image->display_name,
                    'display_url' => asset('/uploads/img/' . $image->file_name),
                    'created_at' => $image->created_at->format('H:i'),
                    'size' => $this->getFileSize($image->file_name)
                ];
            }
            
            return response()->json([
                'success' => true,
                'grouped_images' => $grouped_images, // Change from 'images' to 'grouped_images'
                'pagination' => [
                    'current_page' => $media->currentPage(),
                    'last_page' => $media->lastPage(),
                    'per_page' => $media->perPage(),
                    'total' => $media->total(),
                    'has_more' => $media->hasMorePages()
                ],
                'debug_info' => [
                    'business_id' => $business_id,
                    'query_filters' => [
                        'search' => $search,
                        'date_from' => $date_from,
                        'date_to' => $date_to,
                        'sort' => $sort
                    ],
                    'total_found' => $media->total(),
                    'date_groups_count' => count($grouped_images)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading images: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Upload new images
     */
    public function uploadImages(Request $request)
    {
        try {
            $business_id = $request->session()->get('user.business_id');
            
            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
            ]);
            
            $uploaded_images = [];
            
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    // Generate file name similar to ProductV2Controller
                    $file_name = time() . '_' . mt_rand() . '_' . $file->getClientOriginalName();
                    
                    // Move file to uploads/img directory to match product images
                    $file->move(public_path('uploads/img'), $file_name);
                    
                    // Create media record
                    $media = new Media([
                        'file_name' => $file_name,
                        'business_id' => $business_id,
                        'uploaded_by' => auth()->user()->id,
                        'model_media_type' => 'product_image'
                    ]);
                    $media->save();
                    
                    $uploaded_images[] = [
                        'id' => $media->id,
                        'file_name' => $media->file_name,
                        'display_name' => $media->display_name,
                        'display_url' => asset('/uploads/img/' . $media->file_name),
                        'created_at' => $media->created_at->format('H:i'),
                        'size' => $this->getFileSize($media->file_name)
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'images' => $uploaded_images,
                'message' => count($uploaded_images) . ' image(s) uploaded successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading images: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete an image
     */
    public function deleteImage(Request $request, $id)
    {
        try {
            $business_id = $request->session()->get('user.business_id');
            
            $media = Media::where('business_id', $business_id)->findOrFail($id);
            
            // Delete file from uploads/img directory
            $file_path = public_path('uploads/img/' . $media->file_name);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            // Delete record from database
            $media->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get file size in human readable format
     */
    private function getFileSize($file_name)
    {
        $path = public_path('uploads/img/' . $file_name);
        if (file_exists($path)) {
            $bytes = filesize($path);
            $units = ['B', 'KB', 'MB', 'GB'];
            
            for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                $bytes /= 1024;
            }
            
            return round($bytes, 2) . ' ' . $units[$i];
        }
        
        return 'Unknown';
    }
}
