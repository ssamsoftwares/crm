<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4"  :status="session('status')" />
 <h4 class="text-muted text-center font-size-18"><b>Forgot Password</b></h4>
    <form method="POST" class="form-horizontal mt-3" action="{{ route('password.email') }}">
        @csrf



        <!-- Email Address -->
        <div>
            <x-text-input id="email" placeholder="Email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

          <div class="form-group pb-2 text-center row mt-3">
                                    <div class="col-12">
                                        <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Send Email</button>
                                    </div>
                                </div>
    </form>
</x-guest-layout>
