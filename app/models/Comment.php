<?php
/**
 * Comment Model
 * 评论模型 - 处理文章评论功能
 */
class Comment extends BaseModel
{
    protected $table = 'comments';
    protected $fillable = [
        'post_id', 'parent_id', 'author_name', 'author_email', 
        'author_url', 'content', 'ip_address', 'user_agent', 'status'
    ];
    
    /**
     * 获取文章的评论列表（树形结构）
     */
    public function getPostComments($postId, $status = 'approved')
    {
        $sql = "SELECT c.*, 
                       (SELECT COUNT(*) FROM `{$this->table}` WHERE parent_id = c.id AND status = 'approved') as reply_count
                FROM `{$this->table}` c 
                WHERE c.post_id = ? AND c.status = ? AND c.parent_id = 0
                ORDER BY c.created_at ASC";
        
        $comments = $this->db->fetchAll($sql, [$postId, $status]);
        
        // 获取每个评论的回复
        foreach ($comments as &$comment) {
            $comment['replies'] = $this->getCommentReplies($comment['id'], $status);
        }
        
        return $comments;
    }
    
    /**
     * 获取评论的回复
     */
    public function getCommentReplies($parentId, $status = 'approved')
    {
        $sql = "SELECT * FROM `{$this->table}` 
                WHERE parent_id = ? AND status = ? 
                ORDER BY created_at ASC";
        
        return $this->db->fetchAll($sql, [$parentId, $status]);
    }
    
    /**
     * 添加评论
     */
    public function addComment($data)
    {
        // 数据验证
        $errors = $this->validateComment($data);
        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }
        
        // 内容过滤和安全处理
        $data['content'] = $this->sanitizeContent($data['content']);
        $data['author_name'] = htmlspecialchars(trim($data['author_name']));
        $data['author_email'] = filter_var(trim($data['author_email']), FILTER_VALIDATE_EMAIL);
        $data['author_url'] = !empty($data['author_url']) ? filter_var(trim($data['author_url']), FILTER_VALIDATE_URL) : '';
        
        // 添加系统字段
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $data['status'] = $this->getDefaultStatus($data);
        $data['created_at'] = date('Y-m-d H:i:s');
        
        // 检查是否为垃圾评论
        if ($this->isSpam($data)) {
            $data['status'] = 'spam';
        }
        
        $commentId = $this->create($data);
        
        // 更新文章评论数
        if ($data['status'] === 'approved') {
            $this->updatePostCommentCount($data['post_id']);
        }
        
        return $commentId;
    }
    
    /**
     * 验证评论数据
     */
    private function validateComment($data)
    {
        $errors = [];
        
        if (empty($data['author_name']) || strlen(trim($data['author_name'])) < 2) {
            $errors[] = '姓名至少需要2个字符';
        }
        
        if (empty($data['author_email']) || !filter_var($data['author_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '请输入有效的邮箱地址';
        }
        
        if (empty($data['content']) || strlen(trim($data['content'])) < 5) {
            $errors[] = '评论内容至少需要5个字符';
        }
        
        if (strlen($data['content']) > 1000) {
            $errors[] = '评论内容不能超过1000个字符';
        }
        
        if (!empty($data['author_url']) && !filter_var($data['author_url'], FILTER_VALIDATE_URL)) {
            $errors[] = '请输入有效的网址';
        }
        
        // 检查文章是否存在
        $postModel = new Post();
        if (!$postModel->exists($data['post_id'])) {
            $errors[] = '文章不存在';
        }
        
        // 检查父评论是否存在
        if (!empty($data['parent_id']) && !$this->exists($data['parent_id'])) {
            $errors[] = '回复的评论不存在';
        }
        
        return $errors;
    }
    
    /**
     * 内容安全处理
     */
    private function sanitizeContent($content)
    {
        // 移除危险标签
        $content = strip_tags($content, '<p><br><strong><em><a>');
        
        // 处理链接
        $content = preg_replace_callback(
            '/<a\s+href="([^"]+)"[^>]*>([^<]+)<\/a>/i',
            function($matches) {
                $url = filter_var($matches[1], FILTER_VALIDATE_URL);
                if ($url) {
                    return '<a href="' . htmlspecialchars($url) . '" rel="nofollow" target="_blank">' . 
                           htmlspecialchars($matches[2]) . '</a>';
                }
                return htmlspecialchars($matches[2]);
            },
            $content
        );
        
        // 自动链接化URL
        $content = preg_replace(
            '/(https?:\/\/[^\s<>"]+)/i',
            '<a href="$1" rel="nofollow" target="_blank">$1</a>',
            $content
        );
        
        return trim($content);
    }
    
    /**
     * 获取默认状态
     */
    private function getDefaultStatus($data)
    {
        // 如果开启了评论审核，默认为待审核
        if (defined('COMMENT_MODERATION') && COMMENT_MODERATION) {
            return 'pending';
        }
        
        // 检查是否为已知用户（通过邮箱）
        if ($this->isKnownUser($data['author_email'])) {
            return 'approved';
        }
        
        return 'pending';
    }
    
    /**
     * 检查是否为已知用户
     */
    private function isKnownUser($email)
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}` 
                WHERE author_email = ? AND status = 'approved' 
                LIMIT 1";
        
        return $this->db->fetchColumn($sql, [$email]) > 0;
    }
    
    /**
     * 垃圾评论检测
     */
    private function isSpam($data)
    {
        // 简单的垃圾评论检测规则
        $spamKeywords = ['viagra', 'casino', 'poker', 'loan', 'mortgage'];
        $content = strtolower($data['content']);
        
        foreach ($spamKeywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return true;
            }
        }
        
        // 检查链接数量
        $linkCount = substr_count($content, 'http');
        if ($linkCount > 3) {
            return true;
        }
        
        // 检查重复评论
        if ($this->isDuplicateComment($data)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 检查重复评论
     */
    private function isDuplicateComment($data)
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}` 
                WHERE author_email = ? AND content = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        
        return $this->db->fetchColumn($sql, [$data['author_email'], $data['content']]) > 0;
    }
    
    /**
     * 更新文章评论数
     */
    private function updatePostCommentCount($postId)
    {
        $sql = "UPDATE posts SET comment_count = (
                    SELECT COUNT(*) FROM `{$this->table}` 
                    WHERE post_id = ? AND status = 'approved'
                ) WHERE id = ?";
        
        $this->db->execute($sql, [$postId, $postId]);
    }
    
    /**
     * 审核评论
     */
    public function approveComment($id)
    {
        $comment = $this->getById($id);
        if (!$comment) {
            return false;
        }
        
        $this->update($id, ['status' => 'approved']);
        $this->updatePostCommentCount($comment['post_id']);
        
        return true;
    }
    
    /**
     * 拒绝评论
     */
    public function rejectComment($id)
    {
        $comment = $this->getById($id);
        if (!$comment) {
            return false;
        }
        
        $this->update($id, ['status' => 'rejected']);
        $this->updatePostCommentCount($comment['post_id']);
        
        return true;
    }
    
    /**
     * 标记为垃圾评论
     */
    public function markAsSpam($id)
    {
        $comment = $this->getById($id);
        if (!$comment) {
            return false;
        }
        
        $this->update($id, ['status' => 'spam']);
        $this->updatePostCommentCount($comment['post_id']);
        
        return true;
    }
    
    /**
     * 获取待审核评论数量
     */
    public function getPendingCount()
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}` WHERE status = 'pending'";
        return $this->db->fetchColumn($sql);
    }
    
    /**
     * 获取最近评论
     */
    public function getRecent($limit = 10, $status = 'approved')
    {
        $sql = "SELECT c.*, p.title as post_title, p.slug as post_slug
                FROM `{$this->table}` c
                LEFT JOIN posts p ON c.post_id = p.id
                WHERE c.status = ?
                ORDER BY c.created_at DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$status, $limit]);
    }
}
