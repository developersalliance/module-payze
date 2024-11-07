<?php declare (strict_types=1);

namespace DevAll\Payze\Controller\Adminhtml\Transactions;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'DevAll_Payze::transactions';

    /** @var PageFactory */
    protected $pageFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */

    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ){
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return Page
     */
    public function execute(): Page
    {
        $page = $this->pageFactory->create();
        $page->setActiveMenu('DevAll_Payze::transactions');
        $page->getConfig()->getTitle()->prepend(__('All Transactions'));

        return $page;
    }
}
