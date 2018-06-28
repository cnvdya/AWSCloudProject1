<h2><?= $title ?></h2>
<br>
<br>
<?php if($this->session->userdata('logged_in')){
		$foldername= $this->session->userdata('username').$this->session->userdata('user_id');		
			}else
				$foldername = "john1"; ?>
<?php foreach($posts as $post) : ?>
	<h3><?php echo $post['title']; ?></h3>
	<div class="row">
		<div class="col-md-3">
			<img class="post-thumb" src="http://dor6sckvvmzzd.cloudfront.net/<?php echo $foldername."/".$post['post_image']; ?>"  alt="<?php echo $post['post_image']; ?>">
		</div>
		<div class="col-md-9">
			<small class="post-date">Posted on: <?php echo $post['created_at']; ?> in <strong><?php echo $post['name']; ?></strong></small><br>
		<?php echo word_limiter($post['body'], 60); ?>
		<br><br>
		<p><a class="btn btn-info" style="background-color: #008CBA;" href="<?php echo site_url('/posts/'.$post['slug']); ?>">VIEW</a></p>
		</div>
	</div>
<?php endforeach; ?>
<div class="pagination-links">
		<?php echo $this->pagination->create_links(); ?>
</div>