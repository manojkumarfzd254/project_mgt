<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                	<div class="card-header"><a href="javascript:add_user()" class="btn btn-success">Add User</a></div>
                  <div class="card-body">
                    <h4 class="card-title">All Users</h4>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th> # </th>
                            <th> Name </th>
                            <th> Username </th>
                            <th> Password </th>
                            <th> Role </th>
                            <th>Message</th>
                            <th>Permission</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          		$i=1;
                          		$users = $this->Admin_model->all_users();
                          		foreach ($users->result() as $u) {
                          			echo '<tr>
                          					<td>'.$i++.'</td>
                          					<td>'.ucwords($u->name).'</td>
                          					<td>'.$u->x.'</td>
                          					<td>'.$u->y.'</td>
                          					<td><label class="badge badge-gradient-success">'.$u->role.'</label></td>
                          					';
                          						if($u->role != 'admin')
                          						{
                          							echo '<td><a href="javascript:send_message('.$u->id.')" class="btn btn-success btn-xs">Send Message</a></td>

                                        <td><a href="javascript:list_permission('.$u->id.')" class="btn btn-primary btn-xs">Permission</a></td>';
                          						}
                          			echo '</tr>';
                          		}
                          ?>
                         
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
