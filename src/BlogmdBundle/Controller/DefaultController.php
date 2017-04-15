<?php

namespace BlogmdBundle\Controller;

use BlogmdBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy([], ['publishedAt' => 'DESC']);

        return $this->render('@App/default/index.html.twig', ['posts' => $posts]);
    }


    /**
     * @Route("/posts/{slug}", name="blog_post")
     * @Method("GET")
     *
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Post $post)
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy([], ['publishedAt' => 'DESC']);

        $nextPost = null;
        $previousPost = null;

        foreach ($posts as $i => $p) {
            if ($p->getId() === $post->getId()) {
                if ($i < count($posts) - 1) {
                    $nextPost = $posts[$i + 1];
                }
                break;
            }

            $previousPost = $p;
        }
        return $this->render('@App/default/item.html.twig', [
            'post' => $post,
            'previousPost' => $previousPost,
            'nextPost' => $nextPost,
            ]);
    }

    /**
     * @Route("/markdown-help", name="markdown_help")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mdHelpAction()
    {
        $items = [];

        $items['Headers']['markdown'] = <<<'MARKDOWN'
# H1
## H2
### H3
#### H4
##### H5
###### H6
MARKDOWN;

        $items['Lists']['markdown'] = <<<'MARKDOWN'
* A list
* Of
* Items

* A List
    * With a subitem (indent with 4 spaces)
* Nice..

1. A numbered list
2. Of
3. Items
MARKDOWN;

        $items['Links']['markdown'] = <<<'MARKDOWN'
See: https://www.symfony.com for more information.

See: [The Symfony! home page](https://www.symfony.com) for more information.
MARKDOWN;

        $items['Emphasis']['markdown'] = <<<'MARKDOWN'
*emphasis* or _emphasis_  (e.g., _italics_)

**strong emphasis** or __strong emphasis__ (e.g., **boldface**)


MARKDOWN;
        $items['Code']['markdown'] = <<<'MARKDOWN'
Some text with `some code` inside.

    line 1 of code indented by 4 spaces
    line 2 of code indented by 4 spaces
    line 3 of code indented by 4 spaces

Some text with ```some fenced code``` inside.

```
line 1 of fenced code
line 2 of fenced code
line 3 of fenced code
```

```php
/**
  * Converts a markdown string to HTML.
  *
  * @Route("/preview", name="admin_post_content_preview")
  */
public function previewAction(Request $request)
{
    return $this->json([
        'data' => $this->get('markdown')
            ->toHtml($request->request->get('text')),
    ]);
}
```
MARKDOWN;
        $items['Quotes']['markdown'] = <<<'MARKDOWN'
Normal text
> "This entire paragraph of text will be enclosed in an HTML blockquote element.
Blockquote elements are reflowable. You may arbitrarily
wrap the text to your liking, and it will all be parsed
into a single blockquote element."

More text here
MARKDOWN;
        $items['HRs']['markdown'] = <<<'MARKDOWN'
* * *
***
*****
- - -
MARKDOWN;
        $items['Tables']['markdown'] = <<<'MARKDOWN'
First Header  | Second Header
------------- | -------------
Content Cell  | Content Cell
Content Cell  | Content Cell

### Alignment
If you wish, you can add a leading and tailing pipe to each line of the table.

You can specify alignement for each column by adding colons to separator lines. A colon at the left of the separator line will make the column left-aligned; a colon on the right of the line will make the column right-aligned; colons at both side means the column is center-aligned.

| Item      | Value |
| --------- | -----:|
| Computer  | $1600 |
| Phone     |   $12 |
| Pipe      |    $1 |

### Formatting
You can apply span-level formatting to the content of each cell using regular Markdown syntax:

| Function name | Description                    |
| ------------- | ------------------------------ |
| `help()`      | Display the help window.       |
| `destroy()`   | **Destroy your computer!**     |
MARKDOWN;
        $items['Images']['markdown'] = <<<'MARKDOWN'
![Symfony Logo](http://symfony.com/logos/symfony_black_03.png?v=5)
MARKDOWN;

        foreach ($items as $key => $item) {
            $items[$key]['html'] = $this->get('markdown')->toHtml($item['markdown']);
        }

        return $this->render(
            '@App/default/markdown_test.html.twig',
            [
                'items' => $items,
            ]
        );

    }
}
