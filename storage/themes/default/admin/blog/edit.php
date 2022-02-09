<h1 class="h3 mb-5"><?php ee('Edit Post') ?></h1>
<div class="card">
    <div class="card-body">
        <form method="post" action="<?php echo route('admin.blog.update', [$post->id]) ?>" enctype="multipart/form-data" data-trigger="editor">
            <?php echo csrf() ?>
            <div class="form-group mb-4">
                <label for="title" class="form-label"><?php ee('Title') ?></label>
                <input type="text" class="form-control p-2" name="title" id="title" value="<?php echo $post->title ?>" placeholder="My Sample Post">
            </div>
            <div class="form-group mb-4">
                <label for="slug" class="form-label"><?php ee('Slug') ?></label>
                <input type="text" class="form-control p-2" name="slug" id="slug" value="<?php echo $post->slug ?>" placeholder="my-sample-post">
                <p class="form-text"><?php ee('Leave this empty to automatically generate it from the title.') ?></p>
            </div>
            <?php if($post->image): ?>
                <img src="<?php echo uploads($post->image, 'blog') ?>" width="200" class="mb-3 rounded">
            <?php endif ?>
            <div class="form-group mb-4">
                <label for="image" class="form-label"><?php ee('Featured Image') ?></label>
                <input type="file" class="form-control" name="image" id="image">
                <p class="form-text"><?php ee('You can upload a featured image that will be displayed in the post. A thumbnail will be automatically generated. Recommended size is 720x300.') ?></p>
            </div>
            <div class="form-group mb-4">
                <label for="content" class="form-label"><?php ee('Content') ?></label>
                <p class="form-text"><?php ee('Use the rich editor below to write your articles. To create an excerpt use <strong>{{--more--}}</strong> tag to split the article for the main page.') ?></p>
                <textarea name="content" id="editor"><?php echo $post->content ?></textarea>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="meta_title" class="form-label"><?php ee('Meta Title') ?></label>
                        <input type="text" class="form-control p-2" name="meta_title" id="meta_title" value="<?php echo $post->meta_title ?>">
                        <p class="form-text"><?php ee('If you want to define a custom meta title fill this field otherwise leave it empty to use post title.') ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="meta_description" class="form-label"><?php ee('Meta Description') ?></label>
                        <input type="text" class="form-control p-2" name="meta_description" id="meta_description" value="<?php echo $post->meta_description ?>">
                        <p class="form-text"><?php ee('If you want to define a custom meta description fill this field otherwise leave it empty to use post title.') ?></p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="published" class="form-label"><?php ee('Publish') ?></label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" data-binary="true" id="published" name="published" value="1" <?php echo $post->published ? 'checked' : '' ?>>
                    <label class="form-check-label" for="published"><?php ee('Publish') ?></label>
                </div>
                <p class="form-text"><?php ee('Do you want to publish this post? If you want to save it as draft don\'t publish it now.') ?></p>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary"><?php ee('Update Post') ?></button>
                <div class="ms-auto">
                    <a href="<?php echo route('blog.post', [$post->slug]) ?>" class="btn btn-success" target="_blank"><?php ee('View Post') ?></a>
                </div>
            </div>
        </form>

    </div>
</div>