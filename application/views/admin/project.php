
<?
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
	<?
}
else
{
	?>
	<div class="row">
		<a href="<?=base_url?>Admin/project/<?=$data->id?>/upload" class="btn btn-success">Upload File</a>
	</div>
	<br>
	<?
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
              	}
}
?>

    


