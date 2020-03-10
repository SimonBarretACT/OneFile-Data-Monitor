
<div class="w-full max-w-lg">

<?php $attributes = array('class' => 'bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4');
echo form_open('unarchive/message', $attributes); ?>

<?=form_hidden('learnerId', $learnerId);?>

  <img class="block mx-auto w-40 pb-4" src="<?=base_url('assets/img/undraw_done_a34v.svg');?>" />
<h1 class="text-3xl text-blue-800 text-center">Success</h1>
                    <p class="text-blue-700 text-center pb-3">Your learner has now been restored.</p>
    <div class="mb-4">
      <label class="block text-gray-700 text-sm mb-2" for="username">
        Please enter a comment explaining why you have unarchived this learner:
      </label>
      <input autofocus class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="comment" name="comment" type="text" placeholder="Your comment">
    </div>
    <div class="flex items-center justify-between">
      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" value="submit">
        Send
      </button>
    </div>
  </form>
  <p class="text-center text-blue-800 text-xs">
    &copy;2020 ACT Training Ltd. All rights reserved.
  </p>
</div>

