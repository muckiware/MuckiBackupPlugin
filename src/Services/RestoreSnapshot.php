<?php declare(strict_types=1);
/**
 * MuckiFacilityPlugin
 *
 * @category   SW6 Plugin
 * @package    MuckiFacility
 * @copyright  Copyright (c) 2024 by Muckiware
 * @license    MIT
 * @author     Muckiware
 *
 */
namespace MuckiFacilityPlugin\Services;

use MuckiRestic\Entity\Result\ResultEntity;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\Console\Output\OutputInterface;

use MuckiRestic\Library\Restore;

use MuckiFacilityPlugin\Core\Defaults as PluginDefaults;
use MuckiFacilityPlugin\Core\ConfigPath;
use MuckiFacilityPlugin\Core\BackupTypes;
use MuckiFacilityPlugin\Backup\BackupRunnerFactory;
use MuckiFacilityPlugin\Backup\BackupInterface;
use MuckiFacilityPlugin\Entity\CreateBackupEntity;
use MuckiFacilityPlugin\Entity\BackupPathEntity;
use MuckiFacilityPlugin\Services\Content\BackupRepository;
use MuckiFacilityPlugin\Services\Content\BackupRepositoryChecks;
use MuckiFacilityPlugin\Services\SettingsInterface as PluginSettings;
use MuckiFacilityPlugin\Services\Helper as PluginHelper;
use MuckiFacilityPlugin\Services\ManageRepository as ManageService;
use MuckiFacilityPlugin\Services\CliOutput as ServicesCliOutput;

class RestoreSnapshot
{
    protected array $allResults = [];
    public function __construct(
        protected LoggerInterface $logger,
        protected BackupRepository $backupRepository,
        protected PluginSettings $pluginSettings,
        protected PluginHelper $pluginHelper,
        protected ServicesCliOutput $servicesCliOutput
    )
    {}

    public function getAllResults(): array
    {
        return $this->allResults;
    }

    public function addAllResult(array $allResults): void
    {
        $this->allResults = array_merge($this->allResults, $allResults);
    }

    public function restoreSnapshot(CreateBackupEntity $createBackup, bool $isJsonOutput=true): void
    {
        $restoreClient = $this->prepareRestoreClient(
            $createBackup->getSnapshotId(),
            $createBackup,
            $isJsonOutput
        );

        $restore = $restoreClient->createRestore();
        $this->addAllResult([$restore]);
    }

    public function prepareRestoreClient(
        string $snapshotId,
        CreateBackupEntity $createBackup,
        bool $isJsonOutput=true
    ): Restore
    {
        $restoreClient = Restore::create();
        if($this->pluginSettings->hasOwnResticBinaryPath()) {
            $restoreClient->setBinaryPath($this->pluginSettings->getOwnResticBinaryPath());
        }

        $restoreClient->setRepositoryPassword($createBackup->getRepositoryPassword());
        $restoreClient->setRepositoryPath($createBackup->getRepositoryPath());
        $restoreClient->setRestoreTarget($createBackup->getRestoreTarget());
        $restoreClient->setRestoreItem($snapshotId);
        $restoreClient->setJsonOutput($isJsonOutput);

        return $restoreClient;
    }

    public function prepareRestoreBackup(string $backupRepositoryId): CreateBackupEntity
    {
        if($this->servicesCliOutput->isCli()) {
            $this->servicesCliOutput->printCliOutputNewline('Prepare restore...');
        }

        $backupRepository = $this->backupRepository->getBackupRepositoryById($backupRepositoryId);
        $createBackup = new CreateBackupEntity();

        if($backupRepository) {

            $createBackup->setBackupRepositoryId($backupRepositoryId);
            $createBackup->setBackupType($backupRepository->getType());
            $createBackup->setRestoreTarget($backupRepository->getRestorePath());
            $createBackup->setRepositoryPath($backupRepository->getRepositoryPath());
            $createBackup->setRepositoryPassword($backupRepository->getRepositoryPassword());
        }

        return $createBackup;
    }
}
