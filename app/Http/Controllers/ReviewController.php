<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Barang;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with('barang')->orderBy('id_review', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $reviews = $query->get();
        $barangs = Barang::orderBy('nama', 'asc')->get();

        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('status', 'approved')->count(),
            'pending' => Review::where('status', 'pending')->count(),
            'avg_rating' => round(Review::where('status', 'approved')->avg('rating') ?: 0, 1),
        ];

        return view('review.review', compact('reviews', 'barangs', 'stats'));
    }

    /**
     * Toggle approved / pending status.
     */
    public function toggleStatus($id)
    {
        $review = Review::findOrFail($id);
        $review->status = $review->status === 'approved' ? 'pending' : 'approved';
        $review->save();

        Alert::success('Berhasil', 'Status ulasan berhasil diubah menjadi ' . ucfirst($review->status));
        return redirect()->back();
    }

    /**
     * Remove the specified review.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        Alert::success('Berhasil', 'Ulasan berhasil dihapus.');
        return redirect()->back();
    }
}

