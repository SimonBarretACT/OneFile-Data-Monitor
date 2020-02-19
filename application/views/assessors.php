<div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">

<div class="flex flex-row flex-wrap flex-grow mt-2">

<div class="w-full p-3">
        <!--Table Card-->
        <div class="bg-white border rounded shadow">
            <div class="border-b p-3">
                <h5 class="font-bold uppercase text-gray-600">Assessors and IQAs</h5>
            </div>
            <div class="p-5">
                <table class="w-full p-5 text-gray-700">
                    <thead>
                        <tr>
                            <th class="text-left text-blue-700">Name</th>
                            <th class="text-left text-blue-700">Created</th>
                            <th class="text-left text-blue-700">Last Logged In</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($assessors as $assessor): ?>
                        <tr id="c<?=$assessor['UserID'];?>" class="tableRow hover:bg-gray-300 hover:cursor-pointer">
                            <td><?=$assessor['FirstName'] . ' ' . $assessor['LastName'];?></td>
                            <td><?=date("d.m.y", strtotime($assessor['Created']));?></td>
                            <td><?=(date("d.m.y", strtotime($assessor['DateLastLoggedIn'])) == '01.01.70' ? '' : date("d.m.y", strtotime($assessor['DateLastLoggedIn'])) );?></td>
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

