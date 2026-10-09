<?php require_once __DIR__ . '/header.php'; ?>

<?php require_once __DIR__ . '/../app/Models/Comment.php';
$commentModel = new Comment(); ?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-pencil-square text-primary"></i> What's on your mind?</h5>
                <form action="index.php?page=home" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="3" placeholder="Share something..." required></textarea>
                    </div>
                    <div class="row align-items-center">
                        <div class="col">
                            <label class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-image"></i> Add Photo
                                <input type="file" name="post_image" accept="image/*" class="d-none" id="postImage">
                            </label>
                            <span id="fileName" class="text-muted small ms-2"></span>
                        </div>
                        <div class="col-auto">
                            <button type="submit" name="create_post" class="btn btn-primary btn-sm">
                                <i class="bi bi-send"></i> Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="uploads/<?= htmlspecialchars($post['profile_image']) ?>" alt="Avatar" class="rounded-circle me-3" width="45" height="45">
                            <div>
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($post['name']) ?></h6>
                                <small class="text-muted"><i class="bi bi-clock"></i> <?= date('M d, Y - h:i A', strtotime($post['created_at'])) ?></small>
                            </div>
                            <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                                <div class="ms-auto">
                                    <form action="index.php?page=delete_post" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?')">
                                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <?php if ($post['image']): ?>
                            <img src="uploads/<?= htmlspecialchars($post['image']) ?>" class="img-fluid rounded mb-3" alt="Post image">
                        <?php endif; ?>
                        <hr>
                        <div class="d-flex justify-content-between text-muted">
                            <?php $comments = $commentModel->getByPostId($post['id']); ?>
                            <button class="btn btn-sm btn-link text-decoration-none" data-bs-toggle="collapse" data-bs-target="#comments-<?= $post['id'] ?>">
                                <i class="bi bi-chat-dots"></i> <?= count($comments) ?> Comment<?= count($comments) !== 1 ? 's' : '' ?>
                            </button>
                        </div>

                        <div class="collapse mt-3" id="comments-<?= $post['id'] ?>">
                            <?php foreach ($comments as $comment): ?>
                                <div class="d-flex mb-2">
                                    <img src="uploads/<?= htmlspecialchars($comment['profile_image']) ?>" class="rounded-circle me-2" width="32" height="32">
                                    <div class="bg-light rounded p-2 flex-grow-1">
                                        <small class="fw-bold d-block"><?= htmlspecialchars($comment['name']) ?></small>
                                        <small><?= htmlspecialchars($comment['content']) ?></small>
                                    </div>
                                    <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                                        <form action="index.php?page=delete_comment" method="POST" class="ms-2">
                                            <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-x"></i></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            <form action="index.php?page=add_comment" method="POST" class="d-flex mt-2">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <input type="text" name="content" class="form-control form-control-sm me-2" placeholder="Write a comment..." required>
                                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <p class="text-muted mt-3">No posts yet. Be the first to post!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
