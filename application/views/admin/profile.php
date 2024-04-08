<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                	
                  <div class="card-body">
                    <h4 class="card-title">Profile </h4>
                    <?
                        if($type=='edit'){
                          if($msg = $this->session->flashdata('msg'))echo $msg;
                          ?>
                             <form class="forms-sample " method="post" enctype="multipart/form-data">
                            <input type="hidden" name="status" value="add_user">
                              <div class="form-group">
                                <label for="exampleInputUsername1">Username</label>
                                <input type="text" class="form-control" name="username" id="exampleInputUsername1" placeholder="Username" value="<?=$user->x?>">
                              </div>
                              <div class="form-group">
                                <label for="exampleInputEmail1">Name </label>
                                <input type="text" class="form-control" name="name" id="exampleInputEmail1" placeholder="Name"  value="<?=$user->name?>">
                              </div>
                              <div class="form-group">
                                <label for="exampleInputPassword1"> Password</label>
                                <input type="text" name="password" class="form-control" id="exampleInputPassword1" placeholder="  Password"  value="<?=$user->y?>">
                              </div>
                              <div class="form-group">
                                <label>Avatar</label>
                                <input type="file" name="avatar" class="form-control">
                              </div>
                             
                              <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                        
                            </form>
                          <?
                        }
                        else{
                    ?>
                    <a href="<?=base_url?>Admin/profile/edit" class="btn btn-success">Edit</a> 
                    <div class="table-responsive">
                      <table class="table">
                       <tbody>
                        <tr><th>Avatar</th> <td><img src="<?=$avatar?>" style="width:80px;height: 80px"></td></tr>
                        <tr><th>Name</th> <td><?=$user->name?></td></tr>
                        <tr><th>Useranme</th> <td><?=$user->x?></td></tr>
                        <tr><th>Password</th> <td><?=$user->y?></td></tr>
                        <tr><th>Role</th> <td><?=$user->role?></td></tr>
                       
                         
                       </tbody>
                      </table>
                    </div>
                  <? } ?>
                  </div>
                </div>
              </div>
            </div>