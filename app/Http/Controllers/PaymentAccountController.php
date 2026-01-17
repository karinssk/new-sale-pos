<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\PaymentAccount;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentAccountController extends Controller
{
    /**
     * All Utils instance.
     */

    /**
     * Constructor
     *
     * @param PaymentAccount $payment_account
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        return view('payment_account.index');
    }

    /**
     * Get payment accounts via ajax.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPaymentAccounts()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $payment_accounts = PaymentAccount::where('business_id', $business_id)
                                        ->select(['id', 'name', 'account_number', 'account_type_id', 'note'])
                                        ->with('account_type');

        return DataTables::of($payment_accounts)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                data-toggle="dropdown" aria-expanded="false">' .
                                __("messages.actions") .
                                '<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">';

                if (auth()->user()->can('account.access')) {
                    $html .= '<li><a href="' . action('PaymentAccountController@edit', [$row->id]) . '"><i class="glyphicon glyphicon-edit"></i> ' . __("messages.edit") . '</a></li>';
                }

                if (auth()->user()->can('account.access')) {
                    $html .= '<li><a data-href="' . action('PaymentAccountController@destroy', [$row->id]) . '" class="delete_payment_account_button"><i class="glyphicon glyphicon-trash"></i> ' . __("messages.delete") . '</a></li>';
                }

                $html .= '</ul></div>';

                return $html;
            })
            ->editColumn('account_type_id', function ($row) {
                return !empty($row->account_type) ? $row->account_type->name : '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        // Redirect to account controller
        return redirect()->action('AccountController@create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        // Redirect to account controller
        return redirect()->action('AccountController@store');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PaymentAccount  $paymentAccount
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        // Redirect to account controller
        return redirect()->action('AccountController@show', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PaymentAccount  $paymentAccount
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        // Redirect to account controller
        return redirect()->action('AccountController@edit', [$id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PaymentAccount  $paymentAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        // Redirect to account controller
        return redirect()->action('AccountController@update', [$request, $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PaymentAccount  $paymentAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        // Redirect to account controller
        return redirect()->action('AccountController@destroy', [$id]);
    }
}
