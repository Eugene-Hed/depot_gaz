<?php

namespace App\Helpers;

use Carbon\Carbon;

class TrialManager
{
    const TRIAL_DAYS = 30;
    const TRIAL_START_FILE = 'storage/app/trial_start.txt';

    /**
     * Obtenir la date de début de la période de test
     */
    public static function getTrialStartDate()
    {
        if (file_exists(self::TRIAL_START_FILE)) {
            return Carbon::parse(file_get_contents(self::TRIAL_START_FILE));
        }
        
        // Première utilisation - créer le fichier
        $startDate = Carbon::now();
        @mkdir(dirname(self::TRIAL_START_FILE), 0755, true);
        file_put_contents(self::TRIAL_START_FILE, $startDate->toDateTimeString());
        return $startDate;
    }

    /**
     * Obtenir la date de fin de la période de test
     */
    public static function getTrialEndDate()
    {
        return self::getTrialStartDate()->addDays(self::TRIAL_DAYS);
    }

    /**
     * Obtenir le nombre de jours restants
     */
    public static function getRemainingDays()
    {
        return max(0, Carbon::now()->diffInDays(self::getTrialEndDate(), false));
    }

    /**
     * Vérifier si la période de test est active
     */
    public static function isTrialActive()
    {
        return Carbon::now()->lessThanOrEqualTo(self::getTrialEndDate());
    }

    /**
     * Vérifier si la période de test est expirée
     */
    public static function isTrialExpired()
    {
        return !self::isTrialActive();
    }

    /**
     * Obtenir le statut complet de la période de test
     */
    public static function getTrialStatus()
    {
        return [
            'active' => self::isTrialActive(),
            'expired' => self::isTrialExpired(),
            'start_date' => self::getTrialStartDate(),
            'end_date' => self::getTrialEndDate(),
            'remaining_days' => self::getRemainingDays(),
        ];
    }

    /**
     * Réinitialiser la période de test
     */
    public static function resetTrial()
    {
        if (file_exists(self::TRIAL_START_FILE)) {
            unlink(self::TRIAL_START_FILE);
        }
        return self::getTrialStartDate();
    }
}
