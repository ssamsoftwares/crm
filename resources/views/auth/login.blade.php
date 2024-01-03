<x-guest-layout>
   <!-- Session Status -->

   <div class="wrapper-page">
                  <h4 class="text-muted text-center font-size-18"><b>Sign In</b></h4>
                  <div class="p-3">
                     <form class="form-horizontal mt-3" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group mb-3 row">
                           <div class="col-12">
                              <div>
                                 <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email" />
                                 <x-input-error :messages="$errors->get('email')" class="mt-2" />
                              </div>
                           </div>
                        </div>
                        <div class="form-group mb-3 row">
                           <div class="col-12">
                              <x-text-input id="password" class="form-control"
                                 type="password"
                                 name="password"
                                 placeholder="Password"
                                 required autocomplete="current-password" />
                              <x-input-error :messages="$errors->get('password')" class="mt-2" />
                           </div>
                        </div>
                        <div class="form-group mb-3 row">
                           <div class="col-12">
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                                 <label class="form-label ms-1" for="customCheck1">{{ __('Remember me') }}</label>
                              </div>
                           </div>
                        </div>
                        <div class="form-group mb-3 text-center row mt-3 pt-1">
                           <div class="col-12">
                              <button class="btn btn-info w-100 waves-effect waves-light" type="submit"> {{ __('Log in') }}</button>
                           </div>
                        </div>
                        <div class="form-group mb-0 row mt-2">
                           {{-- <div class="col-sm-7 mt-3">
                              <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock"></i> {{ __('Forgot your password?') }}</a>
                           </div> --}}

                           {{-- <div class="col-sm-5 mt-3">
                            <a href="{{ route('register') }}" class="text-muted"><i class="mdi mdi-account-circle"></i> Create an account</a>
                        </div> --}}
                        </div>
                     </form>
                  </div>
                  <!-- end -->
      <!-- end container -->
   </div>
</x-guest-layout>
