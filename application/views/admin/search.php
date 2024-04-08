<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                	
                  <div class="card-body">
                    <h4 class="card-title">Result (<?=$data->num_rows()?>) </h4>
                    <? if($data->num_rows()): ?>
                   
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                            <tr>
                              <th>Project Name</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?
                              

                              foreach ($data->result() as $d) {
                                 
                                echo '<tr>
                                   
                                    <td><a href="'.base_url.'Admin/project/'.$d->id.'?url='.$d->path.'">'.ucwords($d->label).'</a></td>
                                   
                                </tr>';
                              }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  <? 
              			else:
              				echo '<div class="alert alert-danger" align="center">Project not found.</div>';
                  		endif;
                   ?>
                  </div>
                </div>
              </div>
            </div>