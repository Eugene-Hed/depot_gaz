<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TrialAndLicenseManager
{
    private const INSTALL_FILE = '.installation';
    private const LICENSE_FILE = '.license';
    private const SALT = 'DG-GAZ-2024-SECURE-SALT';
    private const TRIAL_DAYS = 7;

    /**
     * Initialise l'installation si nécessaire
     */
    public function initialize()
    {
        if (!Storage::disk('local')->exists(self::INSTALL_FILE)) {
            $data = [
                'date' => Carbon::now()->toIso8601String(),
                'id' => uniqid('DG-'),
            ];
            Storage::disk('local')->put(self::INSTALL_FILE, encrypt(json_encode($data)));
        }
    }

    /**
     * Récupère les données d'installation
     */
    public function getInstallationData()
    {
        $this->initialize();
        try {
            return json_decode(decrypt(Storage::disk('local')->get(self::INSTALL_FILE)), true);
        } catch (\Exception $e) {
            // En cas de corruption, on réinitialise (ou on bloque)
            return null;
        }
    }

    /**
     * Récupère l'ID d'installation pour le client
     */
    public function getInstallationID()
    {
        $data = $this->getInstallationData();
        return $data ? $data['id'] : 'UNKNOWN';
    }

    /**
     * Vérifie si la licence est valide
     */
    public function isActivated()
    {
        if (!Storage::disk('local')->exists(self::LICENSE_FILE)) {
            return false;
        }

        $key = Storage::disk('local')->get(self::LICENSE_FILE);
        return $this->validateKey($key);
    }

    /**
     * Vérifie si la période d'essai est expirée
     */
    public function isTrialExpired()
    {
        if ($this->isActivated()) {
            return false;
        }

        $data = $this->getInstallationData();
        if (!$data) return true;

        $installDate = Carbon::parse($data['date']);
        return Carbon::now()->diffInDays($installDate) >= self::TRIAL_DAYS;
    }

    /**
     * Nombre de jours restants
     */
    public function getDaysRemaining()
    {
        $data = $this->getInstallationData();
        if (!$data) return 0;

        $installDate = Carbon::parse($data['date']);
        $remaining = self::TRIAL_DAYS - Carbon::now()->diffInDays($installDate);
        return max(0, $remaining);
    }

    /**
     * Valide une clé de licence
     */
    public function validateKey($key)
    {
        $id = $this->getInstallationID();
        $expectedKey = $this->generateKey($id);
        return hash_equals($expectedKey, (string)$key);
    }

    /**
     * Enregistre la licence
     */
    public function activate($key)
    {
        if ($this->validateKey($key)) {
            Storage::disk('local')->put(self::LICENSE_FILE, $key);
            return true;
        }
        return false;
    }

    /**
     * Algorithme de génération de clé (utilisé par l'admin)
     */
    public function generateKey($installationID)
    {
        return strtoupper(substr(hash('sha256', $installationID . self::SALT), 0, 16));
    }
}
