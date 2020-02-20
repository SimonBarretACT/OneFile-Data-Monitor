		
		<div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">

            <div class="flex flex-row flex-wrap flex-grow mt-2">

            <div class="w-full p-3">
                    <!--Table Card-->
                    <div class="bg-white border rounded shadow">
                        <div class="border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Archive Candidates (<?=count($candidates) ;?>)</h5>
                        </div>
                        <div class="p-5">
                            <table id="candidates" class="w-full p-5 text-gray-700">
                                <thead>
                                    <tr>
                                        <th data-sort="string-ins" class="cursor-pointer text-left text-blue-700">Name</th>
                                        <th data-sort="date" class="text-right text-blue-700">Created</th>
                                        <th class="text-right text-blue-700">Last Login</th>
                                        <th class="text-right text-blue-700">Last Modified</th>
										<th data-sort="percentage" class="cursor-pointer text-right text-blue-700">Progress</th>
										<th class="text-center text-blue-700">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($candidates as $candidate): ?>
                                    <tr id="c<?=$candidate['UserID'];?>" class="tableRow hover:bg-gray-300 hover:cursor-pointer">
                                        <td><?=$candidate['FirstName'] . ' ' . $candidate['LastName'];?></td>
                                        <td class="text-right"><?=date("d-m-y", strtotime($candidate['DateCreated']));?></td>
                                        <td class="text-right"><?=(date("d-m-y", strtotime($candidate['DateLogin'])) == '01-01-70' ? '' : date("d-m-y", strtotime($candidate['DateLogin'])) );?></td>
                                        <td class="text-right"><?=($candidate['DateCreated'] == $candidate['DateModified'] ? '' : date("d-m-y", strtotime($candidate['DateModified'])) );?></td>
										<td class="text-right"><?=$candidate['Progress'];?>%</td>
										<td class="text-center"><button class="archiver action-btn text-red-600 font-bold" 
                                            data-learner-id="<?=$candidate['UserID'];?>"
                                            data-learner-name="<?=$candidate['FirstName'] . ' ' . $candidate['LastName'];?>">
                                            Archive</button></td>
                                    </tr>  
                                <?php endforeach; ?>                               
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
    
    $("#candidates").stupidtable({
        "percentage":function(a,b){
            var aNum = a.slice(0, -1)
            var bNum = b.slice(0, -1)
            return parseInt(aNum,10) - parseInt(bNum,10)
        },
        "date":function(a,b){
            return moment(a).isBefore(b, 'day');
        }
    });

    $(".archiver").click(function(){
        let $id = $( this ).data( "learner-id");
        let $name = $( this ).data( "learner-name");

        $.confirm({
            useBootstrap: false,
            title: 'Archive',
            content: 'Are you sure you want to archive <strong>' + $name + '</strong>?',
            icon: 'fas fa-archive',
            autoClose: 'cancel|5000',
            escapeKey: 'cancel',
            boxWidth: '30%',
            buttons: {
                confirm: {
                btnClass: 'btn-red',
                text: 'Archive',
                action: function () {
                    // Make the archive call
                    $.get( "archive/learner/" + $id, function( ) {
                        $('#c' + $id).fadeOut(1500, function() { 
                            $('#c' + $id).remove(); 
                        });
                        $.alert({
                            useBootstrap: false,
                            boxWidth: '30%',
                            title: 'Archive',
                            content: '<strong>' + $name + '&#39;s</strong> portflio has been archived.',
                        });
                    });
                }
            },
            cancel: function () {
                    $.alert({
                        useBootstrap: false,
                        boxWidth: '30%',
                        title: 'Archive',
                        content: 'The archive has been <strong>cancelled</strong>.',
                    });
                }
            }
        });
    });
});
</script>
