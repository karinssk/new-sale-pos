<?php

class PDFSecurityHelper {
    private static $secret = 'pdf-security-key-2025'; // Change this to a secure random string
    
    /**
     * Generate a secure token for PDF access
     */
    public static function generatePDFToken($transactionId, $documentType, $userId = null) {
        $data = [
            'transaction_id' => $transactionId,
            'document_type' => $documentType,
            'user_id' => $userId,
            'expires_at' => time() + (60 * 15), // 15 minutes expiry
            'created_at' => time()
        ];
        
        $payload = base64_encode(json_encode($data));
        $signature = hash_hmac('sha256', $payload, self::$secret);
        
        return $payload . '.' . $signature;
    }
    
    /**
     * Validate PDF access token
     */
    public static function validatePDFToken($token) {
        if (empty($token)) {
            return ['valid' => false, 'error' => 'Token required'];
        }
        
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return ['valid' => false, 'error' => 'Invalid token format'];
        }
        
        list($payload, $signature) = $parts;
        
        // Verify signature
        $expectedSignature = hash_hmac('sha256', $payload, self::$secret);
        if (!hash_equals($expectedSignature, $signature)) {
            return ['valid' => false, 'error' => 'Invalid token signature'];
        }
        
        // Decode payload
        $data = json_decode(base64_decode($payload), true);
        if (!$data) {
            return ['valid' => false, 'error' => 'Invalid token data'];
        }
        
        // Check expiry
        if (time() > $data['expires_at']) {
            return ['valid' => false, 'error' => 'Token expired'];
        }
        
        return [
            'valid' => true,
            'data' => $data
        ];
    }
    
    /**
     * Generate a secure PDF URL with token
     */
    public static function generateSecurePDFUrl($transactionId, $documentType, $userId = null) {
        $token = self::generatePDFToken($transactionId, $documentType, $userId);
        return url("/secure-pdf/{$documentType}/{$transactionId}?token={$token}");
    }
    
    /**
     * Check if user has permission to access transaction
     */
    public static function hasTransactionAccess($transactionId, $userId, $businessId) {
        try {
            // Get transaction details
            $transaction = \App\Transaction::where('id', $transactionId)
                ->where('business_id', $businessId)
                ->first();
                
            if (!$transaction) {
                return false;
            }
            
            // Basic business access check - user must belong to same business
            return true; // For now, allow if transaction exists in same business
            
        } catch (\Exception $e) {
            return false;
        }
    }
}
