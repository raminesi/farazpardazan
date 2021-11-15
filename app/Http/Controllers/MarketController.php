<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarketRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Order;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{
    use ApiResponseTrait;

    /**
     * @OA\Get(
     *   path="/api/warehouse",
     *   tags={"Market"},
     *   summary="Warehouse",
     *   operationId="warehouse",
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example=""),
     *    )
     *   ),
     *)
     **/
    /**
     * warehouse api
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function warehouse()
    {
        $warehouse = Warehouse::first();
        if($warehouse){
            return $this->apiResponse(
            [
                'success' => true,
                'result' => [
                    'quantity' => $warehouse->quantity,
                    'price' => $warehouse->price,
                    'credit' => $warehouse->credit,
                ]
            ]
            );
        }else{
            return $this->respondError('Not found' , 404);
        }
    }

    /**
     * @OA\Post(
     *   path="/api/order",
     *   tags={"Market"},
     *   summary="Order",
     *   operationId="Order",
     *   description="transaction : buy / sell",
     *
     *   @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *      @OA\Property(property="price", type="floatval", format="floatval", example=25.5),
     *      @OA\Property(property="quantity", type="floatval", format="floatval", example=1.5),
     *      @OA\Property(property="transaction", type="string", format="string", example="buy"),
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example=""),
     *    )
     *   ),
     *)
     **/
    /**
     * order api
     *
     * @param MarketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function order(MarketRequest $request)
    {
        try
        {
            $total_price = $request->quantity * $request->price;
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'total_price' => $total_price,
                'transaction' => $request->transaction,
                'status_id' => 1
            ]);
            return $this->apiResponse(
                    [
                        'success' => true,
                        'result' => $order
                    ]
                );
        }catch (\Exception $e){
            return $this->respondError('Order failed');
        }
    }

    /**
     * @OA\Put(
     *   path="/api/order/{order_id}",
     *   tags={"Market"},
     *   summary="Order Confirm",
     *   operationId="OrderConfirm",
     *
     *   @OA\Parameter(
     *      name="order_id",
     *      in="path",
     *      required=true,
     *      example=1,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example=""),
     *    )
     *   ),
     *)
     **/
    /**
     * order api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function order_confirm($id)
    {
        $order = Order::find($id);
        if($order){
            DB::beginTransaction();
            try
            {
                $order->update(['status_id' => 3]);

                DB::commit();
                return $this->apiResponse(
                    [
                        'success' => true,
                        'result' => $order
                    ]
                );
            }catch (\Exception $e){
                DB::rollBack();
                return $this->respondError('Order failed');
            }
        }else{
            return $this->respondError('Not found' , 404);
        }
    }

}
