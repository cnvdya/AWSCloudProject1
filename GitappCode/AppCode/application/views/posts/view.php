<?php 
//set_include_path('\apache2\htdocs\blog\application\controllers');
require 'application/controllers/app/start.php';

$name = $post['post_image'];
$foldername= $this->session->userdata('username').$this->session->userdata('user_id');
/*$result = $s3->getObject(array(
        	'Bucket' => $config['s3']['bucket'],
        	'Key' => "{$name}",
    		));
			
		
			
$objects = $s3->getIterator('ListObjects', [
'Bucket' => $config['s3']['bucket']
 ]);*/
?>

<div class="bs-component">
<h2 class="text-primary" id="indicators"><?php echo $post['title']; ?></h2>
<br><br>
<p>Created by User : <?php echo "    ".strtoupper($user_data['first_name']." ".$user_data['last_name']); ?></p><br>
<p>Created on : <?php echo $post['created_at']; ?></p><br>
<p>Updated on : <?php echo $post['updated_at']; ?></p><br>
<h4 class="text-primary"> Description :</h4>
<p> <?php echo strip_tags($post['body']); ?></p>
<!-- <input type="text" readonly = "readonly" value="<?php //echo strip_tags($post['body']); ?>"></p> -->

<?php if($this->session->userdata('user_id') == $post['user_id']): ?>
	<hr>
	
	<div class="col-lg-12" style = "text-align:left;margin-top:20px">
 <?php echo form_open('/posts/delete/'.$post['id']); ?>
	<a class="btn btn-default" style="background-color: #008CBA;" href="<?php echo base_url(); ?>posts/edit/<?php echo $post['slug']; ?>">Edit</a>
	

	<a class="btn btn-default" style="background-color: #008CBA;" href ="http://dor6sckvvmzzd.cloudfront.net/<?php echo $foldername."/".$name; ?>" download ="<?php $name; ?>">Download</a>
		
	
		<input type="submit" value="Delete" class="btn btn-default" style="background-color: #ff0039;" >
	</form></div>
	
<?php endif; ?>

</div>
