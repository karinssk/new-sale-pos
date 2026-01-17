<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('lang_v1.login') - {{ config('app.name', 'POS') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e0606ff 0%, #d40000ff 100%);
            min-height: 80vh;
        }
        
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 15%;
            animation-delay: 4s;
        }
        
        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            bottom: 10%;
            right: 20%;
            animation-delay: 1s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .login-btn {
            background: linear-gradient(135deg, #ff0000ff 0%, #ff0000ff 100%);
            transition: all 0.3s ease;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 0, 0, 0.4);
        }
    </style>
</head>

<body class="gradient-bg">
    @inject('request', 'Illuminate\Http\Request')
    @php
        $username = old('username');
        $password = null;
        if (config('app.env') == 'demo') {
            $username = 'admin';
            $password = '123456';

            $demo_types = [
                'all_in_one' => 'admin',
                'super_market' => 'admin',
                'pharmacy' => 'admin-pharmacy',
                'electronics' => 'admin-electronics',
                'services' => 'admin-services',
                'restaurant' => 'admin-restaurant',
                'superadmin' => 'superadmin',
                'woocommerce' => 'woocommerce_user',
                'essentials' => 'admin-essentials',
                'manufacturing' => 'manufacturer-demo',
            ];

            if (!empty($_GET['demo_type']) && array_key_exists($_GET['demo_type'], $demo_types)) {
                $username = $demo_types[$_GET['demo_type']];
            }
        }
    @endphp

    <!-- Floating Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Main Container -->
    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        <div class="w-full max-w-6xl flex bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <!-- Left Side - Welcome Section -->
            <div class="hidden lg:flex lg:w-1/2 gradient-bg p-12 flex-col justify-center items-start text-white relative">
                <div class="floating-shapes">
                    <div class="shape"></div>
                    <div class="shape"></div>
                </div>
                
                <div class="relative z-10">
                    <!-- RubyShop Logo on Left Side -->
                    <div class="mb-8">
                        <img src="{{ asset('img/rubyshop-no-bg.png') }}" alt="RubyShop Logo" class="w-32 h-32 object-contain">
                    </div>
                    
                    <h1 class="text-4xl font-bold mb-6">Welcome to RubyShop</h1>
                    <p class="text-lg mb-8 opacity-90">
                        Manage your business with our comprehensive POS system. 
                        Track inventory, process sales, and grow your business efficiently.
                    </p>
                    
                    <!-- Cover Image -->
                    <div class="mt-8">
                        <img src="{{ asset('img/cover.png') }}" alt="Business Management" class="w-full max-w-md rounded-2xl shadow-lg">
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                <div class="max-w-md mx-auto w-full">
                    
                    <!-- Logo -->
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                            <img src="https://www.rubyshop.co.th/storage/logo/rubyshop-nobg.png" alt="RubyShop Logo" class="w-full h-full object-contain">
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">USER LOGIN</h2>
                        <p class="text-gray-600">Sign in to your account</p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-6">
                        {{ csrf_field() }}
                        
                        <!-- Username Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2"></i>@lang('Username')
                            </label>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                value="{{ $username }}"
                                required
                                autofocus
                                placeholder="Enter your username"
                                class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none {{ $errors->has('username') ? 'border-red-500' : '' }}"
                            />
                            @if ($errors->has('username'))
                                <p class="mt-1 text-sm text-red-600">{{ $errors->first('username') }}</p>
                            @endif
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-lock mr-2"></i>@lang('Password')
                                </label>
                                @if (config('app.env') != 'demo')
                                    <a href="{{ route('password.request') }}" class="text-sm text-purple-600 hover:text-purple-800">
                                        @lang('lang_v1.forgot_your_password')
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    value="{{ $password }}"
                                    required
                                    placeholder="Enter your password"
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none pr-12 {{ $errors->has('password') ? 'border-red-500' : '' }}"
                                />
                                <button type="button" id="show_hide_icon" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @if ($errors->has('password'))
                                <p class="mt-1 text-sm text-red-600">{{ $errors->first('password') }}</p>
                            @endif
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <label for="remember" class="ml-2 text-sm text-gray-700">@lang('lang_v1.remember_me')</label>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="login-btn w-full py-3 px-4 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                            @lang('lang_v1.login')
                        </button>
                    </form>

                    <!-- Register Link -->
                    @if (!($request->segment(1) == 'business' && $request->segment(2) == 'register'))
                        @if (config('constants.allow_registration'))
                            <div class="text-center mt-6">
                                <p class="text-gray-600">
                                    {{ __('business.not_yet_registered') }}
                                    <a href="{{ route('business.getRegister') }}@if (!empty(request()->lang)) {{ '?lang=' . request()->lang }} @endif" 
                                       class="text-purple-600 hover:text-purple-800 font-medium">
                                        {{ __('business.register_now') }}
                                    </a>
                                </p>
                            </div>
                        @endif
                    @endif

                    <!-- Demo Section -->
                    @if (config('app.env') == 'demo')
                        <div class="mt-8 p-4 bg-gray-50 rounded-xl">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Demo Accounts</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <button class="demo-login px-3 py-2 text-xs bg-blue-500 text-white rounded-lg hover:bg-blue-600" data-admin="admin">
                                    All In One
                                </button>
                                <button class="demo-login px-3 py-2 text-xs bg-green-500 text-white rounded-lg hover:bg-green-600" data-admin="admin-pharmacy">
                                    Pharmacy
                                </button>
                                <button class="demo-login px-3 py-2 text-xs bg-purple-500 text-white rounded-lg hover:bg-purple-600" data-admin="admin-electronics">
                                    Electronics
                                </button>
                                <button class="demo-login px-3 py-2 text-xs bg-orange-500 text-white rounded-lg hover:bg-orange-600" data-admin="admin-restaurant">
                                    Restaurant
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('show_hide_icon');
            
            toggleButton.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    passwordInput.type = 'password';
                    toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });

            // Demo login buttons
            document.querySelectorAll('.demo-login').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('username').value = this.dataset.admin;
                    document.getElementById('password').value = '{{ $password }}';
                    document.getElementById('login-form').submit();
                });
            });
        });
    </script>
</body>
</html>
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#show_hide_icon').off('click');
            $('.change_lang').click(function() {
                window.location = "{{ route('login') }}?lang=" + $(this).attr('value');
            });
            $('a.demo-login').click(function(e) {
                e.preventDefault();
                $('#username').val($(this).data('admin'));
                $('#password').val("{{ $password }}");
                $('form#login-form').submit();
            });

            $('#show_hide_icon').on('click', function(e) {
            e.preventDefault();
            const passwordInput = $('#password');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                $('#show_hide_icon').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off tw-w-6" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"/><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"/><path d="M3 3l18 18"/></svg>');
            }
            else if (passwordInput.attr('type') === 'text') {
                passwordInput.attr('type', 'password');
                $('#show_hide_icon').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye tw-w-6" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/></svg>');
            }
        });
        })
    </script>
@endsection
