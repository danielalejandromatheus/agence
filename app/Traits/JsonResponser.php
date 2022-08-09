<?php
namespace App\Traits;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait JsonResponser
{
	protected function successResponse($data,$code = 200)
	{
		return response()->json($data,$code);
	}

	protected function errorResponse($message,$code, $data = null)
	{
		return response()->json(['message' => $message, 'status' => false, 'code'=>$code, 'data'=>$data],$code);
	}

	protected function showAll($collection, $code = 200)
	{
		return $this->successResponse(['data' => $collection,'status'=>true, 'code'=> $code], $code);
	}

	protected function showPaginated($collection)
	{
		return $collection;
	}
	protected function showOne($instance, $message = null, $code = 200)
	{
		return $this->successResponse(['data' => $instance,'message' => $message,'status' => true, 'code'=> $code] , $code);
	}

	protected function showMessage($message, $code = 200)
	{
		return $this->successResponse(['message' => $message,'status' => true, 'code'=> $code] , $code);
	}
}
