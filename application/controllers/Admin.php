<?php
class Admin extends CI_Controller
{

	private $data;
	function __construct()
	{
		parent::__construct();
		if(!$this->session->has_userdata('admin_login'))
			redirect(base_url());
		$this->load->helper('file');
		 $this->load->helper('form');

          $this->load->library('form_validation');
	}
	public function index($value='')
	{
		$this->data['title'] = 'Dashboard';
		$this->data['page'] = __FUNCTION__;
		$this->load->view('admin/main',$this->data);
	}
	public function search()
	{
		$query = "SELECT * FROM db_projects where label LIKE '%".$_POST['data']."%' AND login_id = '".$this->session->id."'";
		$this->data['data'] = $this->db->query($query);
		$this->data['title'] = 'Search Result';
		$this->data['page'] = __FUNCTION__;
		$this->load->view('admin/main',$this->data);
	}
	public function user_messages()
	{
		$this->data['title'] = 'Messages';
		$this->data['page'] = __FUNCTION__;
		$this->load->view('admin/main',$this->data);
	}
	public function notifications()
	{
		$this->data['title'] = 'Notifications';
		$this->data['page'] = __FUNCTION__;
		$this->load->view('admin/main',$this->data);
	}
	public function list_projects($value='')
	{
		$this->data['title'] = 'List Projects';
		$this->data['page'] = __FUNCTION__;
		$this->data['list'] = $this->db->get_where('projects',['login_id'=>$this->session->id]);
		$this->load->view('admin/main',$this->data);
	}
	public function profile($type='')
	{
		$this->data['title'] = 'Profile';
		$this->data['page'] = __FUNCTION__;
		$this->data['type'] = $type;
		$this->data['user'] = $this->db->get_where('login',['id'=>$this->session->id])->row();
		if($post = $this->input->post()){


			$this->form_validation->set_rules('username', 'Username', 'required|is_unique[login.x]');
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			if(empty($_FILES['avatar']['name']))
			{
					if($this->form_validation->run() == FALSE){
						$msg = validation_errors('<div class="alert alert-danger">', '</div>');
					}
					else
					{
						$data = array(
										'name'=>$post['name'],
										'x'=>$post['username'],
										'y'=>$post['password'],
										'role'=>'user',
						);
						$this->db->where('id',$this->session->id)->update('login',$data);
						$msg = '<div class="alert alert-success">Details Successfully update.</div>';
					}
			}
			else
			{
					$config['upload_path']          = './avatar/';
	                $config['allowed_types']        = 'gif|jpg|png|jpeg';
	                $config['max_size']             = 10000;
	                $config['max_width']            = 10240;
	                $config['max_height']           = 7680;

	                $this->load->library('upload', $config);

	                if ( ! $this->upload->do_upload('avatar'))
	                {
	                        $msg =  '<div class="alert alert-danger">'.$this->upload->display_errors().'</div>';
	                }
	                else
	                {
	                        $d = $this->upload->data();
	                        $data = array(
									'name'=>$post['name'],
									'x'=>$post['username'],
									'y'=>$post['password'],
									'role'=>'user',
									'avatar'=>$d['file_name'],
					);
	                        $this->db->where('id',$this->session->id)->update('login',$data);
							$msg = '<div class="alert alert-success">Details Successfully update.</div>';
	                }
			}

			$this->session->set_flashdata('msg',$msg);
			redirect(base_url.'Admin/profile/edit');
		}
		else
			$this->load->view('admin/main',$this->data);
	}
	public function manage_role()
	{
		$this->data['title'] = "Manage Role";
		$this->data['page'] = __FUNCTION__;
		$this->load->view('admin/main',$this->data);
	}
	function list_files(){
	    if($post = $this->input->post()){
	        extract($post);
	        $data = ['status' => false,'html'=>$folder];
	        //$data['html'] = $post;
	        
	            $data['html'] = $this->listFolderFiles($url,$project_id);
	        
	        echo json_encode($data);
	    }
	}
	function test(){
	   
	    $tg = new thumbnailGenerator;
	    //echo get_mime_by_extension('uploads/cms_management/cast-converted.pdf');
        $thumb = $tg->generate('uploads/cms_management/IMG20210407170708.jpg', 100, 100);
        echo '<div style="width:100px">';
        echo $thumb;
        echo '</div>';
	}
	function listFolderFiles($dir,$project_id=0){
	    $folder = $dir;
	    $dir = DIR.'/uploads/'.$dir;
	  
	     
        $ffs = scandir($dir);
        $dir  = strtr(
            			rtrim($dir, '/\\'),
            			'/\\',
            			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
            		).DIRECTORY_SEPARATOR;
        $folder  = strtr(
            			rtrim($folder, '/\\'),
            			'/\\',
            			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
            		).DIRECTORY_SEPARATOR;
        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);
    
        // prevent empty ordered elements
        if (count($ffs) < 1 || strpos($dir, '.') !== false)
            return 'This directory is empty.';
        $html = '<div class="row">';
        $check = $this->db->get_where('login',['id'=>$this->session->id])->row();
        foreach($ffs as $ff){
            if(is_dir($dir.$ff)){
              $html .= '
              <div class="col-md-2 col-sm-4 col-xs-3 stretch-card grid-margin remove_'.str_replace(' ', '_', $ff).' ">
					                <div class="card   card-img-holder ">
					                  
					                  <div style="height:40px" class="card-header btn-gradient-primary text-white">
					                        <small class="mb-5 caption-title">'.$ff.'</small>';
					                        
					                        if($check->removes==1 || $this->session->role=='admin')
					                        {
					                        	$html .= '<label class="remove-directory" data-file="'.str_replace(' ', '_', $ff).'" data-url="'.$folder.$ff.'">
					                        <i class="mdi mdi-close" style="cursor:pointer"></i></label>';
					                        }
					                  $html .= '</div>
					                 <a href="'.base_url.'Admin/project/'.$project_id.'/?url='.$folder.$ff.'"  style="color:white;text-decoration: none;">
    					                  <div class="card-body" style="padding:0;height:100px">
    					                    <img src="'.base_url.'static/assets/images/directory_image.jpg" style="width:100%;height:100%" class="" alt="circle-image">
    					                    
    					                  </div> </a>
    					                  <div class="card-footer">
    					                    <h6 class="card-text text-success">Folder <span class="mdi mdi-auto-fix rename_folder" data-name="'.$ff.'" data-url="'.$folder.$ff.'" style="float:right;cursor:pointer"></span></h6>
    					                  </div>
					                 
					                </div>
					    </div>
';  
            }
        }
        foreach($ffs as $ff){
            $card='';
            
            if(!is_dir($dir.$ff)){
                $extension = explode('.', $ff);
              			$ext = end($extension); 
                        	$image = 'uploads/'.$folder.$ff;
              			if($ext === 'pdf'){
              				$card = "bg-gradient-danger";
              				$image = 'static/assets/images/pdf.png';
              			}
              			else if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
              				$card = "bg-gradient-info";
              			
              			}
              			else if($ext == '3gp' || $ext == 'mp4' || $ext == 'MPG' || $ext == 'MP2' || $ext == 'MPEG' || $ext == 'MPE' || $ext == 'MPV' || $ext == 'M4P' || $ext == 'M4V')
              			{
              				$card = "bg-gradient-info";
              				$image = 'static/assets/images/video.png';
              			}
              			else {
              				$card = "bg-gradient-success";
              				if($ext == 'docx')
              				{
              					$image = 'static/assets/images/word.png';
              				}
              				else if($ext == 'pptx')
              				{
              					$image = 'static/assets/images/power.png';
              				}
              				else if($ext == 'xlsx')
              				{
              					$image = 'static/assets/images/exel.jpg';
              				}
              			}
              			
              			$size = get_file_info($dir.$ff);
              			$temp = explode('.', $ff);
           
            $html .= '<div class="col-md-2 col-sm-4 col-xs-3 stretch-card grid-margin remove_'.$temp[0].'">
					                <div class="card   card-img-holder ">
					                  
					                  <div style="height:40px" class="card-header '.$card.' text-white">
					                        <small class="mb-5 caption-title">'.strtoupper($ff).'</small>';
					                        if($check->removes==1 || $this->session->role=='admin')
					                        {
					                        $html .= '<label class="remove-files" data-file="'.$temp[0].'" data-url="'.base_url.'uploads/'.$folder.$ff.'"><i class="mdi mdi-close" style="cursor:pointer"></i></label>';
					                    }
					                  $html .= '</div>
					                  <a href="'.base_url.'uploads/'.$folder.$ff.'" target="_blank" style="color:white;text-decoration: none;" title="'.strtoupper($ff).'">
    					                  <div class="card-body" style="padding:0;height:100px">
    					                    <img src="'.base_url.$image.'" style="width:100%;height:100%" class="" alt="circle-image">
    					                    
    					                  </div>
    					                  <div class="card-footer">
    					                    <h6 class="card-text text-success">Size : '.formatSizeUnits($size['size']).'</h6>
    					                  </div>
					                  </a>
					                </div>
					    </div>';
            }
            
        }
        $html .= '</div>';
        return $html;
    }

	public function project($id = 0 , $type = '')
	{
		//call nahi kar paaunga mamma video call par h thk  ye url me jo id ja rhi h wo form ke sath bhejni h kese  bheju
		$this->data['data'] = $this->db->get_where('projects',['id'=>$id])->row();
		$this->data['title']  = ucwords($this->data['data']->label);
		$this->data['page'] = 'directory';
		$this->data['type'] = $type;
		$this->data['label'] = $this->data['data']->label;
		// $this->data['id'] = $id;
		//.$this->data['path'] ye kaha se aa rha ahi is id se row uth kr  ati h database se
		if(isset($_FILES['file'])){
			if($_FILES['file']['name'] != '')
			{
				$file_names = '';
				$total = count($_FILES['file']['name']);
				
				$uploadDir = strtr(
			rtrim($this->input->get('url'), '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		).DIRECTORY_SEPARATOR;
				
				for ($i=0; $i < $total; $i++) { 
					$filename = $_FILES['file']['name'][$i];
					$extension = pathinfo($filename,PATHINFO_EXTENSION);
					$valid_extensions = array("png","jpg","jpeg","pdf","docx","pptx","xlsx","3gp","mp4","MPG","MP2","MPEG","MPE","MPV","M4P","M4V");
					if(in_array($extension, $valid_extensions)){
						$path = "./uploads/".$uploadDir.$filename;
						move_uploaded_file($_FILES['file']['tmp_name'][$i], $path);
						 
						   if(in_array($extension,['png','jpg'])){
						       $fileName = pathinfo($filename)['filename'];
						       $this->img_thumb($uploadDir,$fileName,$extension);
						   }
						
						$sl = ($i==($total-1))?"":"|||";
						$file_names .= $filename.$sl;
					}
					else{
						echo 0;
					}
				}
				
				if($this->data['data']->files == '')
					$this->db->update('projects',['files'=>$file_names],['id'=>$id]);
				else{
					$obj = $this->data['data']->files;
					$obj2 = $obj.'|||'.$file_names;
					$this->db->update('projects',['files'=>$obj2],['id'=>$id]);
				}

					echo 1;
				
			}
		}
		$this->load->view('admin/main',$this->data);
	}
	function img_thumb($folder, $file, $ext = '.jpg', $width = '400', $height = '400')
    {
        $this->load->library('image_lib');
        ini_set("memory_limit", "-1");
        
        $config1['image_library']  = 'gd2';
        $config1['create_thumb']   = TRUE;
        $config1['maintain_ratio'] = TRUE;
        $config1['width']          = $width;
        $config1['height']         = $height;
        $config1['source_image']   = './uploads/_thumbnails/'.$folder.$file.'_thumb'.$ext;
        
        $this->image_lib->initialize($config1);
        $this->image_lib->resize();
        $this->image_lib->clear();
    }
	public function upload_files()
	{
		
	}
	
	function add_folder(){
	    
	    if($post = $this->input->post()){
	        extract($post);
	        $url  = strtr(
			rtrim($url, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		).DIRECTORY_SEPARATOR;
	          if(is_dir(DIR.'/uploads/'.$url.$name)) {
	              echo 'This Folder Already Exists..';
	          }
	          else{
	              if(strpos($name, '.') !== false ||  (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $name))   )
	                    echo 'Please Enter A Valid Name.';
	              else{
    	              if(mkdir(DIR.'/uploads/'.$url.$name,0700,true) )
    	                 echo 'Folder Created Successfully..';
    	              else
    	                   echo 'Please Enter A Valid Name.';
	              }
	          }
	        
	    }
	}
	public function ajax()
	{
		$return = [];
		if($post = $this->input->post())
		{
			switch ($post['status']) {
				case 'add_project':
					$this->form_validation->set_rules('label', 'Project Name', 'required|is_unique[projects.label]');
					if($this->form_validation->run() == FALSE){
						$return['res'] = validation_errors('<div class="alert alert-danger">', '</div>');
					}
					else{
						$this->Admin_model->add_project(['label'=>$post['label'],'path'=>strtolower(str_replace(' ', '_', $post['label'])).'/','login_id'=>$this->session->id]);
					$return['res'] = '<div class="alert alert-success">Project Successfully Add.</div>';
					}
					
				break;

				case 'get_projects':
					$return['content'] = '';
					$projects = $this->db->get_where('projects',['login_id'=>$this->session->id]);
					$check = $this->db->get_where('login',['id'=>$this->session->id])->row();
		            foreach ($projects->result() as $p) {
		              $return['content'] .= '<li class="nav-item">
		                      <a class="nav-link" href="'.base_url.'Admin/project/'.$p->id.'?url='.$p->path.'">
		                        <span class="menu-title">'.ucwords($p->label).'</span>';
		                        if($check->removes==1 || $this->session->role=='admin')
		                        {
		                        	$return['content'] .= '<i class="mdi mdi-format-list-bulleted menu-icon remove-project" data-id="'.$p->id.'"></i>';
		                        }
		                      $return['content'] .= '</a>
		                    </li>';
		            }
				break;
				case 'remove_file':
				$siteURL='http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].'/noida/';
				$filter = str_replace($siteURL, './', $post['url']);
					if(file_exists($filter))
					{
						unlink($filter);
						$return['res'] = '<div class="alert alert-success">File is deleted.</div>';
					}
					else
						$return['res'] = '<div class="alert alert-danger">File not exists!</div>';
				break;
				case 'list_permission':
					$get = $this->db->get_where('login',['id'=>$post['id']])->row();
					$up = $get->file_upload==1?"checked":"";
					$list = $get->list_projects==1?"checked":"";
					$re = $get->file_reload==1?"checked":"";
					$pro = $get->create_project==1?"checked":"";
					$f = $get->create_folder==1?"checked":"";
					$r = $get->removes==1?"checked":"";
					$return['content'] = '
											<div class="card">
                  <div class="card-body">
                    
                    <span id="per"></span>
                    <div class="list-wrapper">
                      <ul class="d-flex flex-column-reverse todo-list todo-list-custom">
                        <li>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="checkbox " '.$up.'  onclick="set_permission(\'file_upload\','.$post['id'].')" type="checkbox"> File upload <i class="input-helper"></i></label>
                          </div>
                         
                        </li>

                        <li>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="checkbox" '.$list.'  onclick="set_permission(\'list_projects\','.$post['id'].')" type="checkbox"> List projects <i class="input-helper"></i></label>
                          </div>
                         
                        </li>

                        <li>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="checkbox" '.$re.' onclick="set_permission(\'file_reload\','.$post['id'].')" type="checkbox"> File reload <i class="input-helper"></i></label>
                          </div>
                         
                        </li>

						<li>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="checkbox" '.$pro.' onclick="set_permission(\'create_project\','.$post['id'].')" type="checkbox"> Create project <i class="input-helper"></i></label>
                          </div>
                         
                        </li>
                        <li>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="checkbox" '.$f.' onclick="set_permission(\'create_folder\','.$post['id'].')" type="checkbox"> Create folder <i class="input-helper"></i></label>
                          </div>
                         
                        </li>
                        <li>
                          <div class="form-check">
                            <label class="form-check-label">
                              <input class="checkbox" '.$r.' onclick="set_permission(\'removes\','.$post['id'].')" type="checkbox"> Delete Files/Folders <i class="input-helper"></i></label>
                          </div>
                         
                        </li>                        
                      </ul>
                    </div>
                  </div>
                </div>
					';
				break;
				case 'set_role':
					$get = $this->db->get_where('login',['id'=>$post['id']]);
					if($get->num_rows())
					{
						$get = $get->row();
						if($post['per'] == 'file_upload')
						{
							if($get->file_upload)
								$this->db->where('id',$post['id'])->update('login',['file_upload'=>0]);
							else
								$this->db->where('id',$post['id'])->update('login',['file_upload'=>1]);
						}
						else if($post['per'] == 'list_projects')
						{
							if($get->list_projects)
								$this->db->where('id',$post['id'])->update('login',['list_projects'=>0]);
							else
								$this->db->where('id',$post['id'])->update('login',['list_projects'=>1]);
						}
						else if($post['per'] == 'file_reload')
						{
							if($get->file_reload)
								$this->db->where('id',$post['id'])->update('login',['file_reload'=>0]);
							else
								$this->db->where('id',$post['id'])->update('login',['file_reload'=>1]);
						}
						else if($post['per'] == 'create_project')
						{
							if($get->create_project)
								$this->db->where('id',$post['id'])->update('login',['create_project'=>0]);
							else
								$this->db->where('id',$post['id'])->update('login',['create_project'=>1]);
						}
						else if($post['per'] == 'create_folder')
						{
							if($get->create_folder)
								$this->db->where('id',$post['id'])->update('login',['create_folder'=>0]);
							else
								$this->db->where('id',$post['id'])->update('login',['create_folder'=>1]);
						}
						else if($post['per'] == 'removes')
						{
							if($get->removes)
								$this->db->where('id',$post['id'])->update('login',['removes'=>0]);
							else
								$this->db->where('id',$post['id'])->update('login',['removes'=>1]);
						}
						$return['res'] = '<div class="alert alert-success">Permission Update.</div>';
					}
					
				break;
				case 'remove_folder':
					
					//$filter = str_replace(base_url, '', $post['url']);
					$flag = $this->delete_directory('./uploads/'.$post['url']);
					if($flag)
						$return['res'] = '<div class="alert alert-success">Directory are deleted.</div>';
					else
						$return['res'] = '<div class="alert alert-danger">Directory not found.</div>';
				break;

				case 'remove_project':
					$get = $this->db->get_where('projects',['id'=>$post['id']]);
					if($get->num_rows())
					{
						$get = $get->row();
						$flag = $this->delete_directory('./uploads/'.$get->path);
						$this->db->where(['id'=>$post['id']])->delete('projects');
						if($flag)
							$return['res'] = '<div class="alert alert-success">Project are deleted.</div>';
						else
							$return['res'] = '<div class="alert alert-danger">Directory|Project not found.</div>';
					}
				break;

				case 'user_form':
					$return['content'] = '
						<div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                   <span id="form_status"></span>
                    <form class="forms-sample add_user">
                    <input type="hidden" name="status" value="add_user">
                      <div class="form-group">
                        <label for="exampleInputUsername1">Username</label>
                        <input type="text" class="form-control" name="username" id="exampleInputUsername1" placeholder="Username">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Name </label>
                        <input type="text" class="form-control" name="name" id="exampleInputEmail1" placeholder="Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Create Password</label>
                        <input type="text" name="password" class="form-control" id="exampleInputPassword1" placeholder=" Create Password">
                      </div>
                     
                      <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                
                    </form>
                  </div>
                </div>
              </div>
					';
				break;

				case 'add_user':
					$this->form_validation->set_rules('username', 'Username', 'required|is_unique[login.x]');
					$this->form_validation->set_rules('name', 'Name', 'required');
					$this->form_validation->set_rules('password', 'Password', 'required');
					if($this->form_validation->run() == FALSE){
						$return['status'] = validation_errors('<div class="alert alert-danger">', '</div>');
					}
					else{
						$data = array(
										'name'=>$post['name'],
										'x'=>$post['username'],
										'y'=>$post['password'],
										'role'=>'user',
						);
						$this->db->insert('login',$data);
					$return['status'] = '<div class="alert alert-success">User Successfully Add.</div>';
					}
				break;

				case 'edit_project_form':
				$get = $this->db->get_where('projects',['id'=>$post['id']])->row();
					$return['content'] = '<div id="pr"></div>
							<form class="edit_project">
								<input type="hidden" name="id" value="'.$post['id'].'">
								<input type="hidden" name="status" value="edit_project">
								<div class="form-group">
									<label>Project Name</label>
									<input type="text" name="label" class="form-control" value="'.$get->label.'">
								</div>
								<div class="form-group">
									<button class="btn btn-info">Submit</button>
								</div>
							</form>
					';
				break;

				case 'edit_project':
					$this->db->where('id',$post['id'])->update('projects',['label'=>$post['label']]);
					$return['status'] = '<div class="alert alert-success">Project Name Successfully changed.</div>';
				break;

				case 'rename_folder_form':
					$return['content'] = '
							<form class="rename_folder_submit">
							<input type="hidden" name="status" value="submit_change_directory_name">
							<input type="hidden" name="url" value="'.$post['url'].'">
							<input type="hidden" name="old" value="'.$post['name'].'">
								<div class="form-group">
									<label>Folder Name</label>
									<input type="text" class="form-control" name="directory" value="'.$post['name'].'">
								</div>
								<div class="form-group">
									<button class="btn btn-warning">Submit</button>
								</div>
							</form>
					';
				break;

				case 'submit_change_directory_name':
				$url  = strtr(
            			rtrim(DIR, '/\\'),
            			'/\\',
            			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
            		).DIRECTORY_SEPARATOR;
				$old = $url.'uploads/'.$post['url'];
				$old = strtr(
            			rtrim($old, '/\\'),
            			'/\\',
            			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
            		);
				if( $id = is_dir($old)){
					chmod($old, 0777);
					$new = trim($old,$post['old']);
					if(rename($old,$new.$post['directory']))
						$return['status'] = '<div class="alert alert-success">Directory name Successfully changed.</div>';
					else
						$return['status'] = '<div class="alert alert-danger"> Something went wrong</div>';
				}
				else
					$return['status'] = '<div class="alert alert-danger"> Directory not found.</div>';
				break;

				case 'message_form':
					$get = $this->db->get_where('login',['id'=>$post['id']])->row();
					$return['content'] = '
							<div class="container">
							<div id="mes" align="center"></div>
								<form class="submit_message_form">
									<input type="hidden" name="id" value="'.$post['id'].'">
									<input type="hidden" name="status" value="submit_message">
										<h3>'.ucwords($get->name).'</h3>
										<div class="form-group">
											<label>Message</label>
											<textarea class="form-control" name="message" placeholder="Type here..."></textarea>
										</div>
										<div class="form-group">
											<button class="btn btn-success btn-sm">Submit</button>
										</div>
								</form>
								<div id="list_messages">';
								$return['content'] .= '
							<table class="table ">
								<thead>
									<tr>
											<th>Date</th>
											<th>Message</th>
									</tr>
								</thead>
								<tbody>';
									$mes = $this->db->order_by('id desc')->get_where('messages',['login_id'=>$post['id']]);
									foreach ($mes->result() as $m) {
										$return['content'] .= '<tr>
												<td>'.date('d-M-Y (h:i A)',strtotime($m->timestamp)).'</td>
												<td>'.$m->message.'</td>
										</tr>';
									}
								$return['content'] .= '</tbody>
							</table>
					';
								$return['content'] .= '</div>
							</div>
					';
				break;

				case 'submit_message':
					$this->db->insert('messages',['login_id'=>$post['id'],'message'=>$post['message']]);
					$return['status'] = '<div class="alert alert-success">Message Successfully sent.</div>';
					$return['id']  = $post['id'];
				break;

				case 'get_message':
					$return['content'] = '
							<table class="table ">
								<thead>
									<tr>
											<th>Date</th>
											<th>Message</th>
									</tr>
								</thead>
								<tbody>';
									$mes = $this->db->order_by('id desc')->get_where('messages',['login_id'=>$post['id']]);
									foreach ($mes->result() as $m) {
										$return['content'] .= '<tr>
												<td>'.date('d-M-Y (h:i A)',strtotime($m->timestamp)).'</td>
												<td>'.$m->message.'</td>
										</tr>';
									}
								$return['content'] .= '</tbody>
							</table>
					';
				break;
			}
			echo json_encode($return);
		}
	}
	function delete_directory($dirname) {
         if (is_dir($dirname)){
		     $dir_handle = opendir($dirname);
		     if (!$dir_handle)
		          return false;
		     while($file = readdir($dir_handle)) {
		           if ($file != "." && $file != "..") {
		                if (!is_dir($dirname."/".$file))
		                     unlink($dirname."/".$file);
		                else
		                     $this->delete_directory($dirname.'/'.$file);
		           }
		     }
		     closedir($dir_handle);
		     rmdir($dirname);
		     return true;
		 }
		 else
		     return false;
		}
}// ho gya check kro fir dusra kam krna h ye mene kr diya work kr rha h checj it
?>
