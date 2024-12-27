<?php

namespace MuckiFacilityPlugin\Backup\Files;

use Psr\Log\LoggerInterface;

use MuckiRestic\Library\Backup;
use MuckiFacilityPlugin\Core\Defaults as PluginDefaults;
use MuckiFacilityPlugin\Services\SettingsInterface as PluginSettings;
use MuckiFacilityPlugin\Entity\CreateBackupEntity;
use MuckiFacilityPlugin\Entity\BackupPathEntity;
use MuckiFacilityPlugin\Backup\BackupInterface;

class FilesRunner implements BackupInterface
{
    public function __construct(
        protected LoggerInterface $logger,
        protected PluginSettings $pluginSettings,
        protected CreateBackupEntity $createBackup
    ) {}

    public function createBackupData(): void
    {
        $backupClient = $this->prepareBackup();

        /** @var BackupPathEntity $backupPath */
        foreach ($this->createBackup->getBackupPaths() as $backupPath) {

            $backupClient->setBackupPath($backupPath->getBackupPath());
            if($backupPath->isCompress()) {
                $backupClient->setCompress(true);
            }

            $createBackup = $backupClient->createBackup();
            $this->logger->info($createBackup->getOutput(), PluginDefaults::DEFAULT_LOGGER_CONFIG);
        }
    }

    public function getBackupData(): mixed
    {
        return array();
    }

    public function saveBackupData(mixed $data): void
    {
        // TODO: Implement saveBackupData() method.
    }

    public function removeBackupData(): void
    {
        // TODO: Implement removeBackupData() method.
    }

    public function prepareBackup(): Backup
    {
        $backupClient = Backup::create();
        if($this->pluginSettings->hasOwnResticBinaryPath()) {
            $backupClient->setBinaryPath($this->pluginSettings->getOwnResticBinaryPath());
        }

        $backupClient->setRepositoryPassword($this->createBackup->getRepositoryPassword());
        $backupClient->setRepositoryPath($this->createBackup->getRepositoryPath());

        return $backupClient;
    }
}
