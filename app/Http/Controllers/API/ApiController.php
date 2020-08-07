<?php


namespace App\Http\Controllers\API;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class ApiController
{
    use AuthorizesRequests;

    /** @var Model */
    protected $Model;

    /** @var Builder */
    protected $Query;

    /** @var Collection|LengthAwarePaginator */
    protected $Results;

    public function __construct(Model $model)
    {
        $this->Model = $model;
        $this->Query = $this->Model->newQuery();
    }

    protected function addFilters(array $filters): void
    {
        foreach ($filters as $index => $filter) {
            $filter = json_decode($filter);
            if ($filter[1] === 'IN' || $filter[1] === 'in'){
                $this->Query = $this->Query->whereIn($filter[0], $filter[2]);
                continue;
            }
            if ($filter[0] === 'JOIN' || $filter[0] === 'join'){
                //$this->Query = $this->Query->join('table', 'first', 'operator', 'second', 'inner', '');
                $this->Query = $this->Query->addSelect("{$this->Model->getTable()}.*");
                $this->Query = $this->Query->join(
                    $filter[1],
                    $filter[2],
                    $filter[3],
                    $filter[4],
                    $filter[5] ?? 'inner'
                );
                if (isset($filter[6])){
                    $this->Query->whereRaw($filter[6]);
                }
                continue;
            }
            $this->Query = $this->Query->where([$filter]);
        }
    }

    protected function prepareQuery(Request $request): void
    {
        if ($request->filters) {
            $this->addFilters($request->filters);
        }
        if ($request->orderBy) {
            $this->Query = $this->Query->orderBy($request->orderBy);
        }
    }

    protected function filterByLoja(string $guard): void
    {
        if (auth($guard)->user()->getAuthIdentifier() > 1) {
            $this->Query = $this->Query->where(
                'Lojas_idLojas',
                '=',
                auth($guard)->user()->loja->id
            );
        }
    }

    protected function executeQuery(Request $request): void
    {
        if ($request->per_page) {
            $this->Results = $this->Query->paginate($request->per_page);
            return;
        }
        $this->Results = $this->Query->get();
    }

    public function index(Request $request)
    {
        $this->prepareQuery($request);
        $this->executeQuery($request);
        return response()->json($this->Results);
    }

    public function create(Request $request)
    {
        $entity = $this->Model->create($request->all());
        return response()->json($entity);
    }

    public function show($id)
    {
        if (is_array($id)) {
            $results = $this->Model->whereIn('id', $id)->get;
            return response()->json($results);
        }
        $entity = $this->Model->find($id);
        return response()->json($entity);
    }

    public function edit(Request $request, $id)
    {
        $entity = $this->Model->find($id);
        $entity->update($request->all());
        $entity = $this->Model->find($id);

        return response()->json($entity);
    }

    public function destroy($id)
    {
        $this->Model->find($id)->delete();
        return response()->json([true]);
    }
}
