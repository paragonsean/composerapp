<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessAboutUsRequest;
use App\Http\Requests\ProcessAdvertisementRequest;
use App\Http\Requests\ProcessArticleRequest;
use App\Http\Requests\ProcessBlogIntroRequest;
use App\Http\Requests\ProcessBlogOutlinesRequest;
use App\Http\Requests\ProcessBlogOutroRequest;
use App\Http\Requests\ProcessBlogParagraphRequest;
use App\Http\Requests\ProcessBlogPostRequest;
use App\Http\Requests\ProcessBlogSectionRequest;
use App\Http\Requests\ProcessBlogTalkingPointsRequest;
use App\Http\Requests\ProcessBlogTitleRequest;
use App\Http\Requests\ProcessContentSummaryRequest;
use App\Http\Requests\ProcessFreestyleRequest;
use App\Http\Requests\ProcessHashtagsRequest;
use App\Http\Requests\ProcessMetaDescriptionRequest;
use App\Http\Requests\ProcessMissionStatementRequest;
use App\Http\Requests\ProcessNewsletterRequest;
use App\Http\Requests\ProcessContentRewriteRequest;
use App\Http\Requests\ProcessParagraphRequest;
use App\Http\Requests\ProcessCallToActionRequest;
use App\Http\Requests\ProcessPressReleaseRequest;
use App\Http\Requests\ProcessTestimonialRequest;
use App\Http\Requests\ProcessHeadlineRequest;
use App\Http\Requests\ProcessSubheadlineRequest;
use App\Http\Requests\ProcessTweetRequest;
use App\Http\Requests\ProcessTwitterThreadRequest;
use App\Http\Requests\ProcessValuePropositionRequest;
use App\Http\Requests\ProcessVideoDescriptionRequest;
use App\Http\Requests\ProcessVideoTagsRequest;
use App\Http\Requests\ProcessVideoTitleRequest;
use App\Http\Requests\ProcessVisionStatementRequest;
use App\Models\Template;
use App\Models\Category;
use App\Traits\DocumentTrait;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class TemplateController extends Controller
{
    use DocumentTrait;

    /**
     * List the Templates.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = Category::with('templates')->orderByRaw("FIELD(id, 'website', 'marketing', 'social', 'other') ASC")->get();

        $templates = Template::all();

        return view('templates.list', ['categories' => $categories, 'templates' => $templates]);
    }

    /**
     * Show the Article form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function article(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'article', 'template' => $template]);
    }

    /**
     * Process the Article.
     *
     * @param ProcessArticleRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processArticle(ProcessArticleRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'title' => $request->input('title'), 'keywords' => $request->input('keywords'), 'subheadings' => $request->input('subheadings')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'article', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'title' => $request->input('title'), 'keywords' => $request->input('keywords'), 'subheadings' => $request->input('subheadings'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Paragraph form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paragraph(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'paragraph', 'template' => $template]);
    }

    /**
     * Process the Paragraph.
     *
     * @param ProcessParagraphRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processParagraph(ProcessParagraphRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'description' => $request->input('description'), 'keywords' => $request->input('keywords')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'paragraph', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'description' => $request->input('description'), 'keywords' => $request->input('keywords'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Blog Post form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogPost(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'blog-post', 'template' => $template]);
    }

    /**
     * Process the Blog Post.
     *
     * @param ProcessParagraphRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processBlogPost(ProcessBlogPostRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'description' => $request->input('description'), 'keywords' => $request->input('keywords')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'blog-post', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'description' => $request->input('description'), 'keywords' => $request->input('keywords'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Blog Paragraph form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogParagraph(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'blog-paragraph', 'template' => $template]);
    }

    /**
     * Process the Blog Paragraph.
     *
     * @param ProcessParagraphRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processBlogParagraph(ProcessBlogParagraphRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'title' => $request->input('title'), 'subheading' => $request->input('subheading')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'blog-paragraph', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'title' => $request->input('title'), 'subheading' => $request->input('subheading'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Blog Title Generator form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogTitle(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'blog-title', 'template' => $template]);
    }

    /**
     * Process the Blog Title.
     *
     * @param ProcessBlogTitleRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processBlogTitle(ProcessBlogTitleRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'content' => $request->input('content')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'blog-title', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'title' => $request->input('content'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Blog Section form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogSection(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'blog-section', 'template' => $template]);
    }

    /**
     * Process the Blog Section.
     *
     * @param ProcessBlogSectionRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processBlogSection(ProcessBlogSectionRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'title' => $request->input('title'), 'subheading' => $request->input('subheading')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'blog-section', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'title' => $request->input('title'), 'subheading' => $request->input('subheading'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Blog Intro form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogIntro(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'blog-intro', 'template' => $template]);
    }

    /**
     * Process the Blog Intro.
     *
     * @param ProcessBlogIntroRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processBlogIntro(ProcessBlogIntroRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'title' => $request->input('title'), 'content' => $request->input('content')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'blog-intro', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'title' => $request->input('title'), 'content' => $request->input('content'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Blog Outro form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogOutro(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'blog-outro', 'template' => $template]);
    }

    /**
     * Process the Blog Outro.
     *
     * @param ProcessBlogOutroRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processBlogOutro(ProcessBlogOutroRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'title' => $request->input('title'), 'content' => $request->input('content')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'blog-outro', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'title' => $request->input('title'), 'content' => $request->input('content'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Blog Outlines form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogOutlines(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'blog-outlines', 'template' => $template]);
    }

    /**
     * Process the Blog Outlines.
     *
     * @param ProcessBlogOutlinesRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processBlogOutlines(ProcessBlogOutlinesRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'title' => $request->input('title'), 'content' => $request->input('content')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'blog-outlines', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'title' => $request->input('title'), 'content' => $request->input('content'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Blog Talking Points form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogTalkingPoints(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'blog-talking-points', 'template' => $template]);
    }

    /**
     * Process the Blog Talking Points.
     *
     * @param ProcessParagraphRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processBlogTalkingPoints(ProcessBlogTalkingPointsRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'title' => $request->input('title'), 'subheading' => $request->input('subheading')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'blog-talking-points', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'title' => $request->input('title'), 'subheading' => $request->input('subheading'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Content Rewrite form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contentRewrite(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'content-rewrite', 'template' => $template]);
    }

    /**
     * Process the Content Rewrite.
     *
     * @param ProcessParagraphRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processContentRewrite(ProcessContentRewriteRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'content' => $request->input('content')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'content-rewrite', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'content' => $request->input('content'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Content Summary form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contentSummary(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'content-summary', 'template' => $template]);
    }

    /**
     * Process the Content Summary.
     *
     * @param ProcessParagraphRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processContentSummary(ProcessContentSummaryRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'content' => $request->input('content')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'content-summary', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'content' => $request->input('content'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Headline form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function headline(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'headline', 'template' => $template]);
    }

    /**
     * Process the Headline.
     *
     * @param ProcessHeadlineRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processHeadline(ProcessHeadlineRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'product' => $request->input('product'), 'description' => $request->input('description'), 'audience' => $request->input('audience'), 'tone' => $request->input('tone')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'headline', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'product' => $request->input('product'), 'description' => $request->input('description'), 'audience' => $request->input('audience'), 'tone' => $request->input('tone'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Subheadline form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subheadline(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'subheadline', 'template' => $template]);
    }

    /**
     * Process the Subheadline.
     *
     * @param ProcessSubheadlineRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processSubheadline(ProcessSubheadlineRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documentss = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'product' => $request->input('product'), 'description' => $request->input('description'), 'audience' => $request->input('audience'), 'tone' => $request->input('tone')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'subheadline', 'template' => $template, 'documents' => $documentss, 'name' => $request->input('name'), 'product' => $request->input('product'), 'description' => $request->input('description'), 'audience' => $request->input('audience'), 'tone' => $request->input('tone'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Call to Action form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function callToAction(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'call-to-action', 'template' => $template]);
    }

    /**
     * Process the Call to Action.
     *
     * @param ProcessCallToActionRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processCallToAction(ProcessCallToActionRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'product' => $request->input('product'), 'description' => $request->input('description'), 'audience' => $request->input('audience'), 'tone' => $request->input('tone')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'call-to-action', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'product' => $request->input('product'), 'description' => $request->input('description'), 'audience' => $request->input('audience'), 'tone' => $request->input('tone'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Testimonial form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function testimonial(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'testimonial', 'template' => $template]);
    }

    /**
     * Process the Testimonial.
     *
     * @param ProcessTestimonialRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processTestimonial(ProcessTestimonialRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'product' => $request->input('product'), 'description' => $request->input('description')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'testimonial', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'product' => $request->input('product'), 'description' => $request->input('description'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Meta Description form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function metaDescription(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'meta-description', 'template' => $template]);
    }

    /**
     * Process the Meta Description.
     *
     * @param ProcessMetaDescriptionRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processMetaDescription(ProcessMetaDescriptionRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'title' => $request->input('title'), 'keywords' => $request->input('keywords'), 'description' => $request->input('description')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'meta-description', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'title' => $request->input('title'), 'keywords' => $request->input('keywords'), 'description' => $request->input('description'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the About Us form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function aboutUs(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'about-us', 'template' => $template]);
    }

    /**
     * Process the About Us.
     *
     * @param ProcessAboutUsRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processAboutUs(ProcessAboutUsRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'product' => $request->input('product'), 'description' => $request->input('description'), 'audience' => $request->input('audience'), 'tone' => $request->input('tone')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'about-us', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'product' => $request->input('product'), 'description' => $request->input('description'), 'audience' => $request->input('audience'), 'tone' => $request->input('tone'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Advertisement form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function advertisement(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'advertisement', 'template' => $template]);
    }

    /**
     * Process the Advertisement.
     *
     * @param ProcessAdvertisementRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processAdvertisement(ProcessAdvertisementRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'product' => $request->input('product'), 'audience' => $request->input('audience')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'advertisement', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'product' => $request->input('product'), 'audience' => $request->input('audience'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Newsletter form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newsletter(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'newsletter', 'template' => $template]);
    }

    /**
     * Process the Newsletter.
     *
     * @param ProcessNewsletterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processNewsletter(ProcessNewsletterRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'company' => $request->input('company'), 'subject' => $request->input('subject'), 'tone' => $request->input('tone')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'newsletter', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'company' => $request->input('company'), 'subject' => $request->input('subject'), 'tone' => $request->input('tone'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Mission Statement form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function missionStatement(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'mission-statement', 'template' => $template]);
    }

    /**
     * Process the Mission Statement.
     *
     * @param ProcessMissionStatementRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processMissionStatement(ProcessMissionStatementRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'company' => $request->input('company'), 'description' => $request->input('description'), 'tone' => $request->input('tone')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'mission-statement', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'company' => $request->input('company'), 'description' => $request->input('description'), 'tone' => $request->input('tone'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Vision Statement form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function visionStatement(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'vision-statement', 'template' => $template]);
    }

    /**
     * Process the Vision Statement.
     *
     * @param ProcessVisionStatementRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processVisionStatement(ProcessVisionStatementRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'company' => $request->input('company'), 'description' => $request->input('description'), 'tone' => $request->input('tone')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'vision-statement', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'company' => $request->input('company'), 'description' => $request->input('description'), 'tone' => $request->input('tone'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Press Release form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pressRelease(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'press-release', 'template' => $template]);
    }

    /**
     * Process the Press Release.
     *
     * @param ProcessPressReleaseRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processPressRelease(ProcessPressReleaseRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'company' => $request->input('company'), 'description' => $request->input('description'), 'subject' => $request->input('subject'), 'tone' => $request->input('tone')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'press-release', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'company' => $request->input('company'), 'description' => $request->input('description'), 'subject' => $request->input('subject'), 'tone' => $request->input('tone'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Value Proposition form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function valueProposition(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'value-proposition', 'template' => $template]);
    }

    /**
     * Process the Value Proposition.
     *
     * @param ProcessValuePropositionRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processValueProposition(ProcessValuePropositionRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'product' => $request->input('product'), 'description' => $request->input('description'), 'audience' => $request->input('audience'), 'tone' => $request->input('tone')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'value-proposition', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'product' => $request->input('product'), 'description' => $request->input('description'), 'audience' => $request->input('audience'), 'tone' => $request->input('tone'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Hashtags form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function hashtags(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'hashtags', 'template' => $template]);
    }

    /**
     * Process the Hashtags.
     *
     * @param ProcessHashtagsRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processHashtags(ProcessHashtagsRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'description' => $request->input('description')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'hashtags', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'description' => $request->input('description'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Tweet form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tweet(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'tweet', 'template' => $template]);
    }

    /**
     * Process the Tweet.
     *
     * @param ProcessTweetRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processTweet(ProcessTweetRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'description' => $request->input('description')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'tweet', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'product' => $request->input('product'), 'description' => $request->input('description'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Twitter Thread form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function twitterThread(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'twitter-thread', 'template' => $template]);
    }

    /**
     * Process the Twitter Thread.
     *
     * @param ProcessTwitterThreadRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processTwitterThread(ProcessTwitterThreadRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'description' => $request->input('description')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'twitter-thread', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'description' => $request->input('description'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Video Title form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function videoTitle(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'video-title', 'template' => $template]);
    }

    /**
     * Process the Video Title.
     *
     * @param ProcessVideoTitleRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processVideoTitle(ProcessVideoTitleRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'description' => $request->input('description')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'video-title', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'description' => $request->input('description'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Video Description form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function videoDescription(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'video-description', 'template' => $template]);
    }

    /**
     * Process the Video Description.
     *
     * @param ProcessVideoDescriptionRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processVideoDescription(ProcessVideoDescriptionRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'description' => $request->input('description')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'video-description', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'description' => $request->input('description'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Video Tags form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function videoTags(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'video-tags', 'template' => $template]);
    }

    /**
     * Process the Video Tags.
     *
     * @param ProcessVideoTagsRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processVideoTags(ProcessVideoTagsRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['language' => mb_strtolower(config('languages')[$request->input('language')]['iso']), 'title' => $request->input('title')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'video-tags', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'title' => $request->input('title'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }

    /**
     * Show the Freestyle form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function freestyle(Request $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();

        return view('templates.container', ['view' => 'freestyle', 'template' => $template]);
    }

    /**
     * Process the Freestyle.
     *
     * @param ProcessFreestyleRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processFreestyle(ProcessFreestyleRequest $request)
    {
        $template = Template::where('id', $request->segment(2))->firstOrFail();
        $template->views += $template->views + 1;
        $template->save();

        try {
            $documents = $this->documentsStore($request, __($template->prompt, ['prompt' => $request->input('prompt')]));
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error has occurred, please try again.'))->withInput();
        }

        return view('templates.container', ['view' => 'freestyle', 'template' => $template, 'documents' => $documents, 'name' => $request->input('name'), 'prompt' => $request->input('prompt'), 'creativity' => $request->input('creativity'), 'variations' => $request->input('variations'), 'language' => $request->input('language')]);
    }
}
