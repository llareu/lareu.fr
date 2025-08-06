<?php

namespace Core\Actions;

use Core\Actions\RouterAwareAction;
use Core\Router;
use Psr\Http\Message\ResponseInterface;
use Core\Renderer\RendererInterface;
use Core\Session\FlashService;
use Core\Validator;
use Psr\Http\Message\ServerRequestInterface;
use Core\Database\Table;

class CrudAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Table
    */
    protected $table;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var FlashService
     */
    private $flash;

    /**
    * @var string
    */
    protected $viewPath;

    /**
     * @var string
     */
    protected $routePrefix;

    protected $messages = [
        'create' => 'L\'élément a bien été créé.',
        'edit' => 'L\'élément a bien été modifié.',
        'delete' => 'L\'élément a bien été supprimé.'
    ];

    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, Router $router, Table $table, FlashService $flash)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->table = $table;
        $this->flash = $flash;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $this->renderer->addGlobal('viewPath', $this->viewPath);
        $this->renderer->addGlobal('routePrefix', $this->routePrefix);
        if ($request->getMethod() === 'DELETE') {
            return $this->delete($request);
        }
        if (substr((string) $request->getUri(), -3) === 'add') {
            return $this->create($request);
        }

        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }

        return $this->index($request);
    }


    /**
     * Affiche la list des elements
     * @param Request $request
     * @return string
     */
    public function index(ServerRequestInterface $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->table->findPaginated(8, $params['p'] ?? 1);

        return $this->renderer->render($this->viewPath.'/index', compact('items'));
    }


    /**
     * Edite un element
     * @param ServerRequestInterface $request
     * @return ResponseInterface|string
     */
    public function edit(ServerRequestInterface $request)
    {
        $errors = null;
        $item = $this->table->find($request->getAttribute('id'));

        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->update($item->id, $params);
                $this->flash->success($this->messages['edit']);
                return $this->redirect($this->routePrefix.'.index');
            }
            $errors = $validator->getErrors();
            $params['id'] = $item->id;
            $item = $params;
        }

        return $this->renderer->render(
            $this->viewPath.'/edit',
            $this->formParams(compact('item', 'errors'))
        );
    }



    /**
     * creer un element
     * @param ServerRequestInterface $request
     * @return ResponseInterface|string
     */
    public function create(ServerRequestInterface $request)
    {
        $errors = null;
        $item = $this->getNewEntity();
        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->insert($params);
                $this->flash->success($this->messages['create']);
                return $this->redirect($this->routePrefix.'.index');
            }
            $errors = $validator->getErrors();
            $item = $params;
        }

        return $this->renderer->render(
            $this->viewPath.'/create',
            $this->formParams(compact('item', 'errors'))
        );
    }

    /**
     * Supprimer un element
     * @param ServerRequestInterface $request
     */
    public function delete(ServerRequestInterface $request)
    {
        $this->table->delete($request->getAttribute('id'));
        $this->flash->success($this->messages['delete']);
        return $this->redirect($this->routePrefix.'.index');
    }

    protected function getParams(ServerRequestInterface $request): array
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, []);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        return new Validator($request->getParsedBody());
    }

    protected function getNewEntity()
    {
        return [];
    }

    /**
     * Permet de traiter les params à envoyer à la vue
     * @param $params
     * @return array
     */
    protected function formParams(array $params): array
    {
        return $params;
    }
}
