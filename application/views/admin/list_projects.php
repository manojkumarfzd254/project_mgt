<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                	
                  <div class="card-body">
                    <h4 class="card-title">List Projects </h4>
                    
                    
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Project Name</th>
                            <th>Edit</th>
                          
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $i=1;
                            foreach ($list->result() as $p) {
                              echo '<tr>
                                      <td>'.$i++.'</td>
                                      <td>'.ucwords($p->label).'</td>
                                      <td><a href="javascript:edit_project('.$p->id.')" class="btn btn-info btn-xs">Edit</a></td>
                                      
                              </tr>';
                            }
                          ?>
                        </tbody>
                      </table>
                    </div>
                 
                  </div>
                </div>
              </div>
            </div>
