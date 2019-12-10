<div class="bg-blue-600 h-screen w-screen">
    <div class="flex flex-col items-center flex-1 h-full justify-center px-4 sm:px-0">
        <div class="flex rounded-lg shadow-lg w-full sm:w-3/4 lg:w-1/2 bg-white sm:mx-0" style="height: 500px">
            <div class="flex flex-col w-full md:w-1/2 p-4">
                <div class="flex flex-col flex-1 justify-center mb-8">
                    <h1 class="text-4xl text-center font-thin">Password Reset</h1>
                    <div class="w-full mt-4">
                        <?=form_open('signin/reset', array('class' => 'form-horizontal w-3/4 mx-auto'));?>
                            <div class="flex flex-col mt-4">
                                <input id="email" type="text" class="flex-grow h-8 px-2 border rounded border-grey-400" name="email" value="" placeholder="Email">
                            </div>
                            <div class="flex flex-col mt-8">
                                <button type="submit" class="bg-blue-700 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded">
                                    Reset
                                </button>
                            </div>
                        </form>
                        <div class="text-center mt-4">
                            <a class="no-underline hover:underline text-blue-dark text-xs" href="<?=base_url('signin');?>">
                                Take me back to sign in.
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden md:block md:w-1/2 rounded-r-lg" style="background: url('<?=base_url()?>/assets/img/undraw_forgot_password_gi2d.svg'); background-size: contain; background-repeat: no-repeat; background-position: center center;"></div>
        </div>
    </div>
</div>