<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_Shopbybrand
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */

namespace Mavenbird\Shopbybrand\Plugin\Controller;

use Closure;
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\ImportExport\Controller\Adminhtml\Import\Download;

class ImportDownloadSample
{
    public const IMPORT_FILE = 'Mavenbird_brand';
    public const SAMPLE_FILES_MODULE = 'Mavenbird_Shopbybrand';

    /**
     * Requests
     *
     * @var [type]
     */
    protected $request;

    /**
     * Factory for Raw Result
     *
     * @var [type]
     */
    protected $resultRawFactory;

    /**
     * Factory for Read
     *
     * @var [type]
     */
    protected $readFactory;

    /**
     * Components Registrar
     *
     * @var [type]
     */
    protected $componentRegistrar;

    /**
     * Factory for File
     *
     * @var [type]
     */
    protected $fileFactory;

    /**
     * Factory for Redirect
     *
     * @var [type]
     */
    protected $resultRedirectFactory;

    /**
     * Messages Manager
     *
     * @var [type]
     */
    protected $messageManager;

    /**
     * Constructor
     *
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param RawFactory $resultRawFactory
     * @param ReadFactory $readFactory
     * @param ComponentRegistrar $componentRegistrar
     * @param Http $request
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        RawFactory $resultRawFactory,
        ReadFactory $readFactory,
        ComponentRegistrar $componentRegistrar,
        Http $request
    ) {
        $this->fileFactory           = $fileFactory;
        $this->resultRawFactory      = $resultRawFactory;
        $this->readFactory           = $readFactory;
        $this->componentRegistrar    = $componentRegistrar;
        $this->request               = $request;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->messageManager        = $context->getMessageManager();
    }

    /**
     * Around Execute
     *
     * @param Download $download
     * @param Closure $proceed
     * @return void
     */
    public function aroundExecute(
        Download $download,
        Closure $proceed
    ) {
        if ($this->request->getParam('filename') !== self::IMPORT_FILE) {
            return $proceed();
        }

        $fileName         = $this->request->getParam('filename') . '.csv';
        $moduleDir        = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, self::SAMPLE_FILES_MODULE);
        $fileAbsolutePath = $moduleDir . '/Files/Sample/' . $fileName;
        $directoryRead    = $this->readFactory->create($moduleDir);
        $filePath         = $directoryRead->getRelativePath($fileAbsolutePath);

        if (!$directoryRead->isFile($filePath)) {
            /* @var Redirect $resultRedirect */
            $this->messageManager->addErrorMessage(__('There is no sample file for this entity.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/import');

            return $resultRedirect;
        }

        $fileSize = isset($directoryRead->stat($filePath)['size'])
            ? $directoryRead->stat($filePath)['size'] : null;

        $this->fileFactory->create(
            $fileName,
            null,
            DirectoryList::VAR_DIR,
            'application/octet-stream',
            $fileSize
        );

        /* @var Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($directoryRead->readFile($filePath));

        return $resultRaw;
    }
}
