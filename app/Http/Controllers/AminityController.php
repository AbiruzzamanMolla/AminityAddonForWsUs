<?php

namespace Modules\Aminity\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Modules\Aminity\app\Http\Requests\AminityRequest;
use Modules\Aminity\app\Models\Aminity;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class AminityController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(checkAdminHasPermission('listing.aminity.view'), 403);

        $aminities = Aminity::with('translation')->paginate(15);

        return view('aminity::index', compact('aminities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(checkAdminHasPermission('listing.aminity.create'), 403);

        return view('aminity::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AminityRequest $request)
    {
        abort_unless(checkAdminHasPermission('listing.aminity.store'), 403);

        $category = Aminity::create($request->validated());

        $languages = allLanguages();

        $aminityModel = "Modules\\Aminity\\app\\Models\\AminityTranslation";

        $this->generateTranslations(
            $aminityModel,
            $category,
            'aminity_id',
            $request
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.listing.aminity.edit', ['listing_aminity' => $category->id, 'code' => $languages->first()->code]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        abort_unless(checkAdminHasPermission('listing.aminity.view'), 403);

        return view('aminity::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort_unless(checkAdminHasPermission('listing.aminity.edit'), 403);
        $code = request('code') ?? getSessionLanguage();
        abort_unless(Language::where('code', $code)->exists(), 404);

        $aminity   = Aminity::findOrFail($id);
        $languages = allLanguages();

        return view('aminity::edit', compact('aminity', 'code', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AminityRequest $request, $id)
    {
        abort_unless(checkAdminHasPermission('listing.aminity.update'), 403);
        $validatedData = $request->validated();

        $aminity = Aminity::findOrFail($id);

        $aminity->update($validatedData);

        $this->updateTranslations(
            $aminity,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.listing.aminity.edit', ['listing_aminity' => $aminity->id, 'code' => $request->code]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        abort_unless(checkAdminHasPermission('listing.aminity.delete'), 403);
        $aminity = Aminity::findOrFail($id);

        if ($aminity->listings()->count() > 0) {
            return $this->redirectWithMessage(RedirectType::ERROR->value);
        }

        $aminity->translations()->each(function ($translation) {
            $translation->aminity()->dissociate();
            $translation->delete();
        });

        $aminity->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.listing.aminity.index');
    }

    /**
     * @param $id
     */
    public function statusUpdate($id)
    {
        abort_unless(checkAdminHasPermission('listing.aminity.update'), 403);
        $aminity = Aminity::find($id);
        $status  = $aminity->status == 1 ? 0 : 1;
        $aminity->update(['status' => $status]);

        $notification = trans('admin_validation.Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
