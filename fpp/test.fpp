namespace App\Domain\Comment\Event;

data CommentWasEdited = CommentWasEdited { string $id, string $content, ?string $parrentComment, string $post, string $user} deriving (ToArray, FromArray);