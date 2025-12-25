<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Perfume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        
        // If admin, show all transactions. If user, show only their transactions
        if ($user->role === 'admin') {
            $transactions = Transaction::with(['user', 'perfume'])->get();
        } else {
            $transactions = Transaction::with(['perfume'])
                ->where('user_id', $user->id)
                ->get();
        }

        return response()->json([
            'message' => 'Success get all transactions',
            'data' => $transactions
        ], 200);
    }

    /**
     * Store a newly created transaction (checkout).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perfume_id' => 'required|exists:perfumes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $perfume = Perfume::find($request->perfume_id);

        if (!$perfume) {
            return response()->json([
                'message' => 'Perfume not found'
            ], 404);
        }

        // Check stock availability
        if ($perfume->stock < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock. Available stock: ' . $perfume->stock
            ], 400);
        }

        $user = Auth::guard('api')->user();
        $totalPrice = $perfume->price * $request->quantity;

        // Use database transaction to ensure atomicity
        try {
            DB::beginTransaction();

            // Create transaction record
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'perfume_id' => $request->perfume_id,
                'quantity' => $request->quantity,
                'total_price' => $totalPrice,
                'status' => 'completed',
            ]);

            // Decrease stock
            $perfume->stock -= $request->quantity;
            $perfume->save();

            DB::commit();

            return response()->json([
                'message' => 'Transaction completed successfully',
                'data' => $transaction->load('perfume')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Transaction failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified transaction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = Auth::guard('api')->user();
        $transaction = Transaction::with(['perfume', 'user'])->find($id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        // Check if user owns the transaction or is admin
        if ($user->role !== 'admin' && $transaction->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'message' => 'Success get transaction detail',
            'data' => $transaction
        ], 200);
    }
}
