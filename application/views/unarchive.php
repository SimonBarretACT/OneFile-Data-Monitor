		
		<div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">

            <div class="flex flex-row flex-wrap flex-grow mt-2">

            <div class="w-full p-3">
                    <!--Table Card-->
                    <div class="bg-white border rounded shadow">
                        <div class="border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Archived (<?=count($candidates) ;?>)</h5>
                        </div>
                        <div class="p-5">
                            <table class="w-full p-5 text-gray-700">
                                <thead>
                                    <tr>
                                        <th class="text-left text-blue-700">Name</th>
                                        <th class="text-right text-blue-700">Created</th>
                                        <th class="text-right text-blue-700">Last Login</th>
                                        <th class="text-right text-blue-700">Last Modified</th>
										<th class="text-right text-blue-700">Progress</th>
										<th class="text-center text-blue-700">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php 
                                if ($candidates):
                                    foreach ($candidates as $candidate): ?>
                                        <tr id="c<?=$candidate['UserID'];?>" class="tableRow hover:bg-gray-300 hover:cursor-pointer">
                                            <td><?=$candidate['FirstName'] . ' ' . $candidate['LastName'];?></td>
                                            <td class="text-right"><?=date("d-m-y", strtotime($candidate['DateCreated']));?></td>
                                            <td class="text-right"><?=(date("d-m-y", strtotime($candidate['DateLogin'])) == '01-01-70' ? '' : date("d-m-y", strtotime($candidate['DateLogin'])) );?></td>
                                            <td class="text-right"><?=($candidate['DateCreated'] == $candidate['DateModified'] ? '' : date("d-m-y", strtotime($candidate['DateModified'])) );?></td>
                                            <td class="text-right"><?=$candidate['Progress'];?>%</td>
                                            <td class="text-center"><button class="archiver action-btn text-green-600 font-bold" 
                                                data-learner-id="<?=$candidate['UserID'];?>"
                                                data-learner-name="<?=$candidate['FirstName'] . ' ' . $candidate['LastName'];?>">
                                                Unarchive</button></td>
                                        </tr>  
                                    <?php 
                                    endforeach;
                                endif; ?>                               
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!--/table Card-->
                </div>

            </div>
								
					
		</div>
    
<script>
$( document ).ready(function() {
    
    $(".archiver").click(function(){
        let $id = $( this ).data( "learner-id");
        let $name = $( this ).data( "learner-name");

        $.confirm({
            useBootstrap: false,
            title: 'Unarchive',
            content: 'Are you sure you want to unarchive <strong>' + $name + '</strong>?',
            icon: 'fas fa-trash-restore-alt',
            autoClose: 'cancel|5000',
            escapeKey: 'cancel',
            boxWidth: '30%',
            buttons: {
                confirm: {
                btnClass: 'btn-green',
                text: 'Unarchive',
                action: function () {
                    // Make the archive call
                    $.get( "unarchive/learner/" + $id, function( ) {
                        $('#c' + $id).fadeOut(1500, function() { 
                            $('#c' + $id).remove(); 
                        });
                        $.alert({
                            useBootstrap: false,
                            boxWidth: '30%',
                            title: 'Unarchive',
                            content: '<strong>' + $name + '&#39;s</strong> portflio has been unarchived.',
                        });
                    });
                }
            },
            cancel: function () {
                    $.alert({
                        useBootstrap: false,
                        boxWidth: '30%',
                        title: 'Unarchive',
                        content: 'The unarchive has been <strong>cancelled</strong>.',
                    });
                }
            }
        });
    });
});
</script>
