		
		<div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">

            <div class="flex flex-row flex-wrap flex-grow mt-2">

            <div class="w-full p-3">
                    <!--Table Card-->
                    <div class="bg-white border rounded shadow">
                        <div class="border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Archive Candidates</h5>
                        </div>
                        <div class="p-5">
                            <table class="w-full p-5 text-gray-700">
                                <thead>
                                    <tr>
                                        <th class="text-left text-blue-700">Name</th>
                                        <th class="text-left text-blue-700">Created</th>
                                        <th class="text-left text-blue-700">Last Login</th>
                                        <th class="text-left text-blue-700">Last Modified</th>
										<th class="text-left text-blue-700">Progress</th>
										<th class="text-left text-blue-700">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($candidates as $candidate): ?>
                                    <tr class="tableRow hover:bg-gray-300 hover:cursor-pointer">
                                        <td><?=$candidate['FirstName'] . ' ' . $candidate['LastName'];?></td>
                                        <td><?=date("d.m.y", strtotime($candidate['DateCreated']));?></td>
                                        <td><?=(date("d.m.y", strtotime($candidate['DateLogin'])) == '01.01.70' ? '' : date("d.m.y", strtotime($candidate['DateLogin'])) );?></td>
                                        <td><?=($candidate['DateCreated'] == $candidate['DateModified'] ? '' : date("d.m.y", strtotime($candidate['DateModified'])) );?></td>
										<td><?=$candidate['Progress'];?>%</td>
										<td><a href="#" class="action-btn text-red-600 font-bold">Archive</a></td>
                                    </tr>  
                                <?php endforeach; ?>                               
                                </tbody>
                            </table>

                            <!-- <p class="py-2"><a href="#">See More issues...</a></p> -->

                        </div>
                    </div>
                    <!--/table Card-->
                </div>

            </div>
								
					
		</div>
		
