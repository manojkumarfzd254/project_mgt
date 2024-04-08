
<?php
if($type=='upload')
{
	?>
	<div class="row"><code>Max upload number of files : 25</code></div>
		<div id="statuss"></div>
		<form action="<?=base_url?>Admin/project/<?=$data->id?>"
		      class="dropzone"
		      id="my-awesome-dropzone"></form>
		      <br>
		      <div id="previews"></div>
		      <button id="upload_btn" class="btn btn-primary">Upload</button><br>
	<?php
}
else
{
	?>
	<div id="statuss"></div>
	<div class="row btn-group">
	    <?php
	    $dir = $this->input->get('url');
		
	    $url = 'target="_blank" href="'.base_url.'Admin/project/'.$data->id.'/upload?url='.@$_GET['url'].'"';
	    if(!isset($_GET['url']) || !isset($_GET) || strpos($dir, '.') !== false){
	        $url = 'onclick="$.alert(\'Something Went Wrong..\'); reutrn false"';
	    }
	    if(strpos($dir, '.') !== false)
		    $dir = '';
	    ?>
	    <?php if($login->file_upload || $this->session->role == 'admin'){ ?>
			<a <?=$url?> class="btn btn-success">Upload File</a>
		<?php } ?>
		<?php if($login->file_reload || $this->session->role == 'admin'){ ?>
			<a href="javascript:reload()" class="btn btn-info"><i class="mdi mdi-refresh"></i> Reload</a>
		<?php } ?>
		<?php if($login->create_folder || $this->session->role == 'admin'){ ?>
			<a href="javascript:create_dir()" class="btn btn-success"><i class="mdi mdi-plus"></i> Create Directory</a>
		<?php } ?>
		<input type="hidden" id="project_id" value="<?=$data->id?>">
		<?php
		$dir = $this->input->get('url');
		if(strpos($dir, '.') !== false)
		    $dir = '';
		?>
		<input type="hidden" id="dir" value="<?=$dir?>">
		<input type="hidden" id="folder" value="<?=$data->path?>">
	</div>
	<br>
	<div class="row" style="
    background: white;
    min-height: 100px;
">
	    <div class="col-md-12 all-files" style="
    padding: 20px;
">
	        
	    </div>
	</div>
	
	<script>
	    function reload(){
	        list_dir();
	    }
	    function create_dir(){
	        let url = $('#dir').val(),
	        project_id = $('#project_id').val(),
	        folder = $('#folder').val();
	     if(url && project_id && folder){
	        $.confirm({
	            type : 'green',
	            title :'Create Folder',
	            content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Enter Folder Name</label>' +
                '<input type="text" placeholder="Folder Name" class="name form-control" required />' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Submit',
                        btnClass: 'btn-blue',
                        action: function () {
                            var name = this.$content.find('.name').val();
                            if(!name){
                                $.alert('provide a valid Folder name');
                                return false;
                            }
                            
                            $.ajax({
                	            type : 'POST',
                	            url : '<?=site_url('Admin/add_folder')?>',
                	            data : {
                	                project_id : project_id,
                	                url : url,
                	                folder : folder,
                	                name : name
                	            },
                	            success:function(res){
                	                $.alert(res);
                	                list_dir();
                	            },
                	            error:function(a,b,c){
                	                $('.all-files').html(a.responseText);
                	            }
                	        })
                            
                            
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });
	     }
	     else{
	         $.alert('<b style="color:red;font-size:2em">Something Went Wrong PLease Try Again.</b>');
	     }
	        
	    }
	       list_dir();
	    function list_dir(){
	        let url = $('#dir').val(),
	        project_id = $('#project_id').val(),
	        folder = $('#folder').val();
	           if(url && project_id && folder){
	        console.log(url+','+project_id+','+folder);
	       $('.all-files').html('<i class="fa fa-spin fa-spinner"></i> Loading Files...');
	        $.ajax({
	            type : 'POST',
	            url : '<?=site_url('Admin/list_files')?>',
	            data : {
	                project_id : project_id,
	                url : url,
	                folder : folder
	            },
	            dataType:'json',
	            success:function(res){
	                $('.all-files').html(res.html);
	            },
	            error:function(a,b,c){
	                $('.all-files').html(a.responseText);
	            }
	        })
	           }
	           else{
	               $('.all-files').html('<b style="color:red;font-size:2em">Something Went Wrong PLease Try Again.</b>');
	           }
	    }
	</script>
	<?php
	/*
		$fileList = explode('|||', $data->files);
        if($data->files != ''){
	?>
	<div class="row">
              

              <?
              		
              		foreach ($fileList as $key => $value) {
              			$extension = explode('.', $value);
              			$ext = end($extension);

              			if($ext === 'pdf')
              				$card = "bg-gradient-danger";
              			else if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png')
              				$card = "bg-gradient-info";
              			else 
              				$card = "bg-gradient-success";
              			$size = get_file_info('./uploads/'.$data->path.$value);
              			
              			?>
              					<div class="col-md-4 stretch-card grid-margin">
					                <div class="card <?=$card?> card-img-holder text-white">
					                  <a href="<?=base_url?>uploads/<?=$data->path.$value?>" target="_blank" style="color:white;text-decoration: none;"><div class="card-body">
					                    <img src="<?=base_url?>static/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
					                    <h4 class="font-weight-normal mb-3"><?=$value?>
					                    </h4>
					                    <h2 class="mb-5"><?=strtoupper($ext)?></h2>
					                    <h6 class="card-text">Size : <?=$size['size']?></h6>
					                  </div></a>
					                </div>
					              </div>
              			<?
              		}
              	
              ?>
              
            </div>

	<?
	}
              	else
              	{
              		echo '<div class="alert alert-danger" align="center">No file here...</div>';
              	}*/
}
?>

    <script>
        $(document).on('click','.remove-files',function(){
            let url = $(this).data('url');
            let f  = $(this).data('file');

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
                            			data:{url:url,status:"remove_file"},
                            			dataType:"json",
                            			beforeSend:function(){
                            				$("#Loading").show();
                            			},
                            			success:function($res){
                            				//alert($res.res);
                            				$(".remove_"+f).hide();
                            				$("#statuss").html($res.res);
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
        });
        $(document).on('click','.remove-directory',function(){
            let url = $(this).data('url');
            let f  = $(this).data('file');

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
                            			data:{url:url,status:"remove_folder"},
                            			dataType:"json",
                            			beforeSend:function(){
                            				$("#Loading").show();
                            			},
                            			success:function($res){
                            				//alert($res.res);
                            				$(".remove_"+f).hide();
                            				$("#statuss").html($res.res);
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
   let  L;
        $(document).on('click','.rename_folder',function(){
            $url = $(this).data('url');
            $name = $(this).data('name');
           L= $.dialog({
            type:"orange",
            title:"Rename Folder",
            icon:"fa fa-directory",
            content: function () {
                var self = this;
                return $.ajax({
                    url: "<?=base_url?>Admin/ajax",
                    data:{status:"rename_folder_form",url:$url,name:$name},
                    dataType: 'json',
                    method: 'post',
                }).done(function (response) {
                    self.setContent(response.content);
                }).fail(function(){
                    self.setContent('Something went wrong.');
                });
            }
        });
        });
        $(document).on('submit','.rename_folder_submit',function(e){
            e.preventDefault();
          //  alert(0);
            $.ajax({
                        url:"<?=base_url?>Admin/ajax",
                        type:"post",
                        data:$(this).serialize(),
                        dataType:"json",
                        beforeSend:function(){
                            $("#Loading").show();
                        },
                        success:function($res){
                            console.log($res);
                            $("#statuss").html($res.status);
                            L.close();
                            return reload();
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
      </script>
