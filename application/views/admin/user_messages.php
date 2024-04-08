<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                	
                  <div class="card-body">
                    <h4 class="card-title">Admin Messages </h4>
                    
                   
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                            <tr>
                              <th>Date</th>
                              <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                              $mes = $this->db->order_by('id desc')->get_where('messages',['login_id'=>$this->session->id]);

                              foreach ($mes->result() as $n) {
                                 $u = $this->db->get_where('login',['id'=>$n->login_id])->row();
                                echo '<tr>
                                    <td>'.date('d-M-Y h:i A',strtotime($n->timestamp)).'</td>
                                    <td>'.$n->message.'</td>
                                   
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
