<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use App\Models\ServiceModel;
use App\Models\FeaturesModel;
use App\Models\HowItStepsModel;
use App\Models\FaqsModel;
use App\Models\PromotionsModel;
use App\Models\RatingModel;

class Landing extends BaseController
{
    public function index()
    {
        $settingsModel = new SettingsModel();
        $serviceModel = new ServiceModel();
        $featuresModel = new FeaturesModel();
        $stepsModel = new HowItStepsModel();
        $faqsModel = new FaqsModel();
        $promoModel = new PromotionsModel();
        $ratingModel = new RatingModel();

        $data = [
            'settings' => $settingsModel->getAll(),
            'services' => $serviceModel->getActiveServices(),
            'featured_services' => $serviceModel->getFeatured(),
            'features' => $featuresModel->getActive(),
            'steps' => $stepsModel->getActive(),
            'faqs' => $faqsModel->getActive(),
            'promotions' => $promoModel->getActive(),
            'hero_promo' => $promoModel->getHeroPromo(),
            'social_proof' => $ratingModel->getSocialProof(),
            'testimonials' => $ratingModel->getTestimonials(3),
        ];

        return view('landing', $data);
    }
}
