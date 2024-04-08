<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                	
                  <div class="card-body">
                    <h4 class="card-title">All Notifications </h4>
                    
                   
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                            <tr>
                              <th>Name</th>
                              <th>Type/Role</th>
                              <th>Login Time</th>
                              <th>Browser</th>
                              <th>Device Name</th>
                              <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                              $noti = $this->db->order_by('id desc')->get_where('notifications',['login_type'=>'user']);
                              foreach ($noti->result() as $n) {
                                 $u = $this->db->get_where('login',['id'=>$n->login_id])->row();
                                echo '<tr>
                                    <td>'.ucwords($u->name).'</td>
                                    <td>'.$n->login_type.'</td>
                                    <td>'.date('d-M-Y (h:i A)',$n->login_time).'</td>
                                    <td>'.ucwords($n->browser).'</td>
                                    <td>'.$n->device.'</td>
                                    <td>'.$n->ip_address.'</td>
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
