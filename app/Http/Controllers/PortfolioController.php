<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\ServiceCategory;
use App\Models\ProjectType;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        return view('portfolios');
    }

    public function create()
    {
        $categories = ServiceCategory::where('status', 'active')->get();
        $projectTypes = ProjectType::where('status', 'active')->get();
        
        return view('portfolio-form', compact('categories', 'projectTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'nullable',
            'images.*' => 'image|max:2048',
            'service_category_id' => 'required|exists:service_categories,id',
            'project_type_id' => 'required|exists:project_types,id',
            'status' => 'required|in:active,inactive'
        ]);

        $portfolio = new Portfolio();
        $portfolio->uuid = (string) Str::uuid();
        $portfolio->title = $request->title;
        $portfolio->description = $request->description;
        $portfolio->service_category_id = $request->service_category_id;
        $portfolio->project_type_id = $request->project_type_id;
        $portfolio->status = $request->status;
        $portfolio->user_id = Auth::id();

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            
            // Store the first image as the main image
            $portfolio->image = ImageHelper::storeAndResize($images[0], 'portfolios');

            // Store additional images
            if (count($images) > 1) {
                $additionalImages = [];
                foreach (array_slice($images, 1) as $image) {
                    $additionalImages[] = ImageHelper::storeAndResize($image, 'portfolios');
                }
                $portfolio->additional_images = $additionalImages;
            }
        }

        $portfolio->save();

        return redirect()->route('portfolios.index')
            ->with('message', 'Portfolio created successfully.');
    }

    public function edit(Portfolio $portfolio)
    {
        $categories = ServiceCategory::where('status', 'active')->get();
        $projectTypes = ProjectType::where('status', 'active')->get();
        
        return view('portfolio-form', compact('portfolio', 'categories', 'projectTypes'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'nullable',
            'images.*' => 'image|max:2048',
            'service_category_id' => 'required|exists:service_categories,id',
            'project_type_id' => 'required|exists:project_types,id',
            'status' => 'required|in:active,inactive'
        ]);

        $portfolio->title = $request->title;
        $portfolio->description = $request->description;
        $portfolio->service_category_id = $request->service_category_id;
        $portfolio->project_type_id = $request->project_type_id;
        $portfolio->status = $request->status;

        if ($request->hasFile('images')) {
            // Delete old images
            if ($portfolio->image) {
                ImageHelper::deleteImage($portfolio->image);
            }
            if (!empty($portfolio->additional_images)) {
                foreach ($portfolio->additional_images as $oldImage) {
                    ImageHelper::deleteImage($oldImage);
                }
            }

            $images = $request->file('images');
            
            // Store new main image
            $portfolio->image = ImageHelper::storeAndResize($images[0], 'portfolios');

            // Store new additional images
            if (count($images) > 1) {
                $additionalImages = [];
                foreach (array_slice($images, 1) as $image) {
                    $additionalImages[] = ImageHelper::storeAndResize($image, 'portfolios');
                }
                $portfolio->additional_images = $additionalImages;
            } else {
                $portfolio->additional_images = null;
            }
        }

        $portfolio->save();

        return redirect()->route('portfolios.index')
            ->with('message', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        // Delete images
        if ($portfolio->image) {
            ImageHelper::deleteImage($portfolio->image);
        }
        if (!empty($portfolio->additional_images)) {
            foreach ($portfolio->additional_images as $image) {
                ImageHelper::deleteImage($image);
            }
        }

        $portfolio->delete();

        return redirect()->route('portfolios.index')
            ->with('message', 'Portfolio deleted successfully.');
    }
} 