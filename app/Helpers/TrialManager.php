<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class TrialManager
{
    const TRIAL_DAYS = 7;
    const INSTALL_FILE = 'installation_data.json';
    const LICENSE_FILE = 'license.key';
    const SALT = 'DEPOT-GAZ-SECRET-2024';

    /**
     * Initialise les données d'installation
     */
    public static function init()
    {
        if (!Storage::disk('local')->exists(self::INSTALL_FILE)) {
            $data = [
                'start_date' => Carbon::now()->toDateTimeString(),
                'install_id' => 'DG-' . strtoupper(substr(md5(uniqid()), 0, 8))
            ];
            Storage::disk('local')->put(self::INSTALL_FILE, json_encode($data));
        }
    }

    /**
     * Récupère les données d'installation
     */
    public static function getInstallData()
    {
        self::init();
        return json_decode(Storage::disk('local')->get(self::INSTALL_FILE), true);
    }

    /**
     * Récupère l'ID d'installation (à donner à l'admin pour générer la clé)
     */
    public static function getInstallID()
    {
        return self::getInstallData()['install_id'];
    }

    /**
     * Obtenir la date de fin de la période de test
     */
    public static function getTrialEndDate()
    {
        $data = self::getInstallData();
        return Carbon::parse($data['start_date'])->addDays(self::TRIAL_DAYS);
    }

    /**
     * Obtenir le nombre de jours restants
     */
    public static function getRemainingDays()
    {
        if (self::isActivated()) return 999;
        return (int) ceil(Carbon::now()->diffInDays(self::getTrialEndDate(), false));
    }

    /**
     * Vérifier si l'application est activée par licence
     */
    public static function isActivated()
    {
        if (!Storage::disk('local')->exists(self::LICENSE_FILE)) {
            return false;
        }

        $key = Storage::disk('local')->get(self::LICENSE_FILE);
        return self::validateKey($key);
    }

    /**
     * Vérifier si la période de test est encore valide
     */
    public static function isTrialActive()
    {
        if (self::isActivated()) return true;
        return Carbon::now()->lessThanOrEqualTo(self::getTrialEndDate());
    }

    /**
     * Vérifier si l'application est bloquée (essai fini et pas de licence)
     */
    public static function isBlocked()
    {
        return !self::isTrialActive();
    }

    /**
     * Générer une clé de licence (utilisé par l'admin)
     */
    public static function generateLicenseKey($installID)
    {
        return 'LIC-' . strtoupper(substr(hash('sha256', $installID . self::SALT), 0, 16));
    }

    /**
     * Valider une clé saisie
     */
    public static function validateKey($key)
    {
        $expected = self::generateLicenseKey(self::getInstallID());
        return hash_equals($expected, (string)$key);
    }

    /**
     * Enregistrer la clé
     */
    public static function activate($key)
    {
        if (self::validateKey($key)) {
            Storage::disk('local')->put(self::LICENSE_FILE, $key);
            return true;
        }
        return false;
    }

    /**
     * Obtenir le statut complet (pour compatibilité avec les vues existantes)
     */
    public static function getTrialStatus()
    {
        return [
            'active' => !self::isActivated(),
            'activated' => self::isActivated(),
            'remaining_days' => self::getRemainingDays(),
            'end_date' => self::getTrialEndDate(),
            'install_id' => self::getInstallID(),
        ];
    }
}
