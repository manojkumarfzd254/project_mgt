<?php
$login = $this->Admin_model->all_users($this->session->id)->row();
$avatar = $login->avatar==''?base_url."static/assets/images/faces/face1.jpg":base_url."avatar/".$login->avatar;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?=ucwords($title)?></title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?=base_url?>static/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?=base_url?>static/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    
    <link rel="stylesheet" href="<?=base_url?>static/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="<?=base_url?>static/assets/images/favicon.ico" />
    <link rel="stylesheet" href="<?=base_url?>static/dropzone.css">
    
      <script src="<?=base_url?>static/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="<?=base_url?>static/assets/vendors/chart.js/Chart.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="<?=base_url?>static/assets/js/off-canvas.js"></script>
    <script src="<?=base_url?>static/assets/js/hoverable-collapse.js"></script>
    <script src="<?=base_url?>static/assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="<?=base_url?>static/assets/js/dashboard.js"></script>
    <script src="<?=base_url?>static/assets/js/todolist.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!-- End custom js for this page -->
    <script src="<?=base_url?>static/dropzone.js"></script>
    <style>
        .caption-title {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
        .remove-files{
            position: absolute;
            color: white;
            top: 11px;
            right: 10px;
        }
        .remove-directory{
            position: absolute;
            color: white;
            top: 11px;
            right: 10px;
        }
    </style>
    <style>
         #Loading
        {
          position: fixed;
          height: 100%;
          width: 100%;
          background-color: #2d1f3566;
          z-index: 99999;
          display: none;
        }
    </style>
  </head>
  <body>
    <div id="Loading">
       <center> 
       <div style="width:15%;height:15%;margin-top:20%">
          <img src="<?=base_url()?>loader.gif" style="width:100%;height:100%">
    </div> </center>
   
      </div>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="<?=base_url?>Admin"><img src="<?=base_url?>static/assets/images/logo.svg" alt="logo" /></a>
          <a class="navbar-brand brand-logo-mini" href="<?=base_url?>Admin"><img src="<?=base_url?>static/assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="<?=base_url?>Admin/search" method="post">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
                <input type="text" name="data" class="form-control bg-transparent border-0" placeholder="Search projects">
              </div>
            </form>
          </div>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="<?=$avatar?>" alt="image">
                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black"><?=ucwords($this->session->name)?></p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                <?php if($this->session->role == 'admin'){ ?>
                <a class="dropdown-item" href="<?=base_url?>Admin/manage-role">
                  <i class="mdi mdi-cached mr-2 text-success"></i> Manage Role</a>
                <?php } ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?=base_url?>">
                  <i class="mdi mdi-logout mr-2 text-primary"></i> Signout </a>
              </div>
            </li>
            <li class="nav-item d-none d-lg-block full-screen-link">
              <a class="nav-link">
                <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
              </a>
            </li>
            <?php
              if($this->session->role == 'admin')
              {
                ?>
                    <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count-symbol bg-danger"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <h6 class="p-3 mb-0">Notifications</h6>
                <div class="dropdown-divider"></div>
                <?php
                    $all = $this->db->order_by('id desc')->limit(3)->get_where('notifications',['login_type'=>'user']);
                    foreach ($all->result() as $a) {
                      $user = $this->db->get_where('login',['id'=>$a->login_id])->row();
                      echo '

                        <a class="dropdown-item preview-item">
                      <div class="preview-thumbnail">
                        <div class="preview-icon bg-success">
                          <i class="mdi mdi-calendar"></i>
                        </div>
                      </div>
                      <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                        <h6 class="preview-subject font-weight-normal mb-1">'.ucwords($user->name).': '.date('d-M-Y (h:i A)',$a->login_time).'</h6>
                        <p class="text-gray ellipsis mb-0"> <b>  are loggedin time are '.date('d-M-Y (h:i A)',$a->login_time).'</b> </p>
                      </div>
                    </a>
                    <div class="dropdown-divider"></div>

                      ';
                    }
                ?>
                
               
                <a href="<?=base_url?>Admin/notifications"><h6 class="p-3 mb-0 text-center">See all notifications</h6></a>
              </div>
            </li>
                <?php
              }
              else
              {
                ?>
                    <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-email-outline"></i>
                <span class="count-symbol bg-warning"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <h6 class="p-3 mb-0">Messages</h6>
                <div class="dropdown-divider"></div>
                <?php
                  $user_mes = $this->db->order_by('id desc')->limit(3)->get_where('messages',['login_id'=>$this->session->id]);
                  $admin = $this->db->get_where('login',['role'=>'admin'])->row();
                  foreach ($user_mes->result() as $um) {
                    ?>
                      <a class="dropdown-item preview-item">
                      <div class="preview-thumbnail">
                        <img src="<?=base_url?>avatar/<?=$admin->avatar?>" alt="image" class="profile-pic">
                      </div>
                      <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                        <h6 class="preview-subject ellipsis mb-1 font-weight-normal"><b><?=ucwords($admin->name)?>:</b> <?=$um->message?></h6>
                        <p class="text-gray mb-0"> <?=date('d-M-Y h:i A',strtotime($um->timestamp))?> </p>
                      </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <?php
                  }

                ?>
                
               <a href="<?=base_url?>Admin/user-messages"> <h6 class="p-3 mb-0 text-center"> All messages</h6></a>
              </div>
            </li>
                <?php
              }
            ?>
            
            

            <li class="nav-item nav-logout d-none d-lg-block">
              <a class="nav-link" href="<?=base_url?>">
                <i class="mdi mdi-power"></i>
              </a>
            </li>
            <li class="nav-item nav-settings d-none d-lg-block">
              <a class="nav-link" href="#">
                <i class="mdi mdi-format-line-spacing"></i>
              </a>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="<?=base_url?>Admin/profile" class="nav-link">
                <div class="nav-profile-image">
                  <img src="<?=$avatar?>" alt="profile">
                  <span class="login-status online"></span>
                  <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2"><?=ucwords($this->session->name)?></span>
                  <span class="text-secondary text-small"><?=ucwords($this->session->role)?></span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?=base_url.'Admin'?>">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <?php if($login->list_projects==1 || $this->session->role == 'admin'){ ?>
           <span id="menu"></span>
         <?php } ?>
           <?php if($login->create_project==1 || $this->session->role == 'admin'){ ?>
            <li class="nav-item sidebar-actions">
              <span class="nav-link">
                
                <button class="btn btn-block btn-lg btn-gradient-primary mt-4 add_project">+ Add a project</button>
                <div class="mt-4">
                  
                 
                </div>
              </span>
            </li>
          <?php } ?>
          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            <div class="page-header">
                <a href="<?=goBack()?>">
              <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                  <i class="mdi mdi-arrow-left"></i>
                </span> <? echo isset($label)?$label:"Dashboard"; ?>
              </h3></a>
              <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                  </li>
                </ul>
              </nav>
            </div>
            <?php
                if(file_exists(VIEWPATH.'/admin/'.$page.'.php'))
                  include VIEWPATH.'/admin/'.$page.'.php';
                else
                  $this->load->view('page404');
            ?>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="container-fluid clearfix">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © acubeapps  <?=date('Y')?></span>
              <!--span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin templates </a> from Bootstrapdash.com</span-->
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    
  
  </body>
</html>
<script type="text/javascript">
  $(".add_project").click(function(){
    $html = '<form class="submit_project_form" method="post">\
    <div id="status"></div>\
    <div class="form-group">\
        <label>Project Name</label>\
        <input type="text" class="form-control" name="label" placeholder="Enter Project Name" required>\
    </div>\
    <div class="form-group">\
        <button class="btn btn-danger bb">Add</button>\
    </div>\
    \</form>';
      $.dialog({
        type:"purple",
        title:"Add Project",
        icon:"fa fa-plus",
        content:$html,
      });
  });
  $(document).on('submit','.submit_project_form',function(e){
    e.preventDefault();
    $data = $(this).serialize()+'&status=add_project';
    $.ajax({
              url:"<?=base_url?>Admin/ajax",
              type:"post",
              data:$data,
              dataType:"json",
              beforeSend:function(){
                $(".bb").html('<i class="fa fa-spinner fa-spin"></i>').prop("disabled",true);
              },
              success:function($res){
                $("#status").html($res.res);
                return get_projects();
              },
              complete:function(){
                $(".bb").html('Add').prop("disabled",false);
              },
              error:function(a,b,c){
                $.alert({
                          type:"red",
                          title:"Error",
                          icon:"fa fa-times-circle",
                          content:a.responseText,
                });
              }
    });
  });
  get_projects();
  function get_projects() {
    $.ajax({
              url:"<?=base_url?>Admin/ajax",
              type:"post",
              data:{status:"get_projects"},
              dataType:"json",
              beforeSend:function(){
                $("#menu").html('<li class="nav-item"><i class="fa fa-spinner fa-spin"></i></li>');
              },
              success:function(res){
                $("#menu").html(res.content);
              },
              complete:function(){

              },
              error:function(a,b,c){
                 $.alert({
                          type:"red",
                          title:"Error",
                          icon:"fa fa-times-circle",
                          content:a.responseText,
                });
              }
    });
  }
</script>
    <script type="text/javascript">

    Dropzone.autoDiscover = false;
          var myDropzone = new Dropzone("#my-awesome-dropzone", {
            url:window.location.href,
            parallelUploads:100,
            uploadMultiple:true,
            maxFilesize:2048,
            timeout: 180000,
            acceptedFiles:'.png,.jpg,.jpeg,.pdf,.docx,.pptx,.xlsx,.3gp,.mp4,.MPG,.MP2,.MPEG,.MPE,.MPV,.M4P,.M4V',
            autoProcessQueue:false,
            success:function(file,response){
                if(response)
                {
                    $("#statuss").html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> File successfully upload.</div>');
                }
                else{
                    $("#statuss").html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Error in File upload.</div>');
                }
            

            }
          });
          $("#upload_btn").click(function(){
            myDropzone.processQueue();
          });
      </script>
      <script type="text/javascript">
        $(document).on('click','.remove-project',function(){
          $id = $(this).data('id');

            $.confirm({
                type : 'red',
                title : 'Confimation!',
                icon:'mdi mdi-bell',
                content : 'Are you sure for delete.',
                buttons:{
                    ok : {
                        text:'Delete',
                        btnClass:'btn-danger',
                        action:function(){
                            //alert(url); 
                            $.ajax({
                                  url:"<?=base_url?>Admin/ajax",
                                  type:"post",
                                  data:{id:$id,status:"remove_project"},
                                  dataType:"json",
                                  beforeSend:function(){
                                    $("#Loading").show();
                                  },
                                  success:function($res){
                                   // alert($res.res);
                                    //$(".remove_"+f).hide();
                                    $("#statuss").html($res.res);
                                    location.href="<?=base_url?>Admin";
                                  },
                                  complete:function(){
                                    $("#Loading").hide();
                                  },
                                  error:function(a,b,c){
                                    $.alert({
                                        type:"red",
                                        title:"Error",
                                        icon:"fa fa-times-circle",
                                        content:a.responseText,
                                    });
                                  }
                            });
                        }
                    },
                    cancel:function(){

                    }
                }
            });
            return false;
        })
      </script>
      <script type="text/javascript">
        function list_permission($id)
        {
          $.dialog({
            type:"purple",
            title:"Role Permission",
            columnClass:"col-md-12",
            icon:"fa fa-lock",
            content: function () {
                var self = this;
                return $.ajax({
                    url: "<?=base_url?>Admin/ajax",
                    data:{status:"list_permission",id:$id},
                    dataType: 'json',
                    method: 'post',
                }).done(function (response) {
                    self.setContent(response.content);
                }).fail(function(){
                    self.setContent('Something went wrong.');
                });
            }
        });
        }
        function set_permission($per,id){
            //alert($per);
            $.ajax({
                    url:"<?=base_url?>Admin/ajax",
                    data:{per:$per,status:"set_role",id:id},
                    dataType:"json",
                    type:"post",
                    beforeSend:function(){
                      $("#Loading").show();
                    },
                    success:function($res){
                      $("#per").html($res.res);
                    },
                    complete:function(){
                      $("#Loading").hide();
                    },
                    error:function(a,b,c){
                      $.alert({
                            type:"red",
                            title:"Error",
                            icon:"fa fa-times-circle",
                            content:a.responseText,
                      });
                    }
            });
        }
        function add_user()
        {
          $.dialog({
            type:"green",
            title:"Add User",
            columnClass:"col-md-12",
            icon:"fa fa-lock",
            content: function () {
                var self = this;
                return $.ajax({
                    url: "<?=base_url?>Admin/ajax",
                    data:{status:"user_form"},
                    dataType: 'json',
                    method: 'post',
                }).done(function (response) {
                    self.setContent(response.content);
                }).fail(function(){
                    self.setContent('Something went wrong.');
                });
            }
        });
        }
        $(document).on('submit','.add_user',function(e){
          e.preventDefault();
        //  alert($(this).serialize());
          $.ajax({
                  url:"<?=base_url?>Admin/ajax",
                  type:"post",
                  data:$(this).serialize(),
                  dataType:"json",
                  beforeSend:function(){
                    $("#Loading").show();
                  },
                  success:function($res){
                    $("#form_status").html($res.status);
                    $(".add_user")[0].reset();
                  },
                  complete:function(){
                    $("#Loading").hide();
                  },
                  error:function(a,b,c){
                    $.alert({
                            title:"Error",
                            type:"red",
                            icon:"fa fa-times-circle",
                            content:a.responseText,
                    });
                  }
          });
        });
        function edit_project($id)
        {
          $.dialog({
            type:"blue",
            title:"Edit Project",
            icon:"fa fa-tag",
            content: function () {
                var self = this;
                return $.ajax({
                    url: "<?=base_url?>Admin/ajax",
                    data:{status:"edit_project_form",id:$id},
                    dataType: 'json',
                    method: 'post',
                }).done(function (response) {
                    self.setContent(response.content);
                }).fail(function(){
                    self.setContent('Something went wrong.');
                });
            }
        });
        }
        
      </script>
      <script type="text/javascript">
        $(document).on('submit','.edit_project',function(e){
            e.preventDefault();
            //alert();
            $.ajax({
                    url:"<?=base_url?>Admin/ajax",
                    type:"post",
                    data:$(this).serialize(),
                    dataType:"json",
                    beforeSend:function(){
                      $("#Loading").show();
                    },
                    success:function(res){
                     $("#pr").html(res.status);
                      setTimeout(location.reload(),2000);
                    },
                    complete:function(){
                      $("#Loading").hide();
                    },
                    error:function(a,b,c){
                      $.alert({
                                type:"red",
                                title:"Error",
                                icon:"fa fa-times-circle",
                                content:a.responseText,
                      });
                    }
            });
        });
        function send_message($id)
        {
          $.dialog({
            type:"green",
            title:"Send Message",
            icon:"fa fa-reply",
            columnClass:"col-md-12",
            content: function () {
                var self = this;
                return $.ajax({
                    url: "<?=base_url?>Admin/ajax",
                    data:{status:"message_form",id:$id},
                    dataType: 'json',
                    method: 'post',
                }).done(function (response) {
                    self.setContent(response.content);
                    
                }).fail(function(){
                    self.setContent('Something went wrong.');
                });
            }
        });
        }
        $(document).on('submit','.submit_message_form',function(e){
          e.preventDefault();
          //alert($(this).serialize());
          $.ajax({
                  url:"<?=base_url?>Admin/ajax",
                  type:"post",
                  data:$(this).serialize(),
                  dataType:"json",
                  beforeSend:function(){
                    $("#Loading").show();
                  },
                  success:function($res){
                    $("#mes").html($res.status);
                    return get_message($res.id);
                  },
                  complete:function(){
                    $("#Loading").hide();
                  },
                  error:function(a,b,c){
                    $.alert({
                                type:"red",
                                title:"Error",
                                icon:"fa fa-times-circle",
                                content:a.responseText,
                      });
                  }
          });
        });
        function get_message($id){
          $.ajax({
                  url:"<?=base_url?>Admin/ajax",
                  type:"post",
                  data:{status:"get_message",id:$id},
                  dataType:"json",
                  beforeSend:function(){
                    $("#list_messages").html('<i class="text-danger">Loading.....</i>');
                  },
                  success:function($res){
                    $("#list_messages").html($res.content);
                  },
                  error:function(a,b,c){
                    $.alert({
                                type:"red",
                                title:"Error",
                                icon:"fa fa-times-circle",
                                content:a.responseText,
                      });
                  }
          });
        }
      </script>
     
  