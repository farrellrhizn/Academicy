<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Golongan;

class ProfileController extends Controller
{
    /**
     * Show profile edit form for dosen
     */
    public function editDosen()
    {
        $dosen = Auth::guard('dosen')->user();
        return view('profile.edit-dosen', compact('dosen'));
    }

    /**
     * Update dosen profile
     */
    public function updateDosen(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        $request->validate([
            'Nama' => 'required|string|max:255',
            'Alamat' => 'required|string',
            'Nohp' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'Nama' => $request->Nama,
            'Alamat' => $request->Alamat,
            'Nohp' => $request->Nohp,
        ];

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($dosen->profile_photo && Storage::exists('public/profile_photos/' . $dosen->profile_photo)) {
                Storage::delete('public/profile_photos/' . $dosen->profile_photo);
            }

            $file = $request->file('profile_photo');
            $fileName = 'dosen_' . $dosen->NIP . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile_photos', $fileName);
            $data['profile_photo'] = $fileName;
        }

        $dosen->update($data);

        return redirect()->back()->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Show profile edit form for mahasiswa
     */
    public function editMahasiswa()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $golongan = Golongan::all();
        return view('profile.edit-mahasiswa', compact('mahasiswa', 'golongan'));
    }

    /**
     * Update mahasiswa profile
     */
    public function updateMahasiswa(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $request->validate([
            'Nama' => 'required|string|max:255',
            'Alamat' => 'required|string',
            'Nohp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'Nama' => $request->Nama,
            'Alamat' => $request->Alamat,
            'Nohp' => $request->Nohp,
        ];

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($mahasiswa->profile_photo && Storage::exists('public/profile_photos/' . $mahasiswa->profile_photo)) {
                Storage::delete('public/profile_photos/' . $mahasiswa->profile_photo);
            }

            $file = $request->file('profile_photo');
            $fileName = 'mahasiswa_' . $mahasiswa->NIM . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile_photos', $fileName);
            $data['profile_photo'] = $fileName;
        }

        $mahasiswa->update($data);

        return redirect()->back()->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Delete profile photo
     */
    public function deletePhoto(Request $request)
    {
        $userType = $request->input('user_type');
        
        if ($userType === 'dosen') {
            $user = Auth::guard('dosen')->user();
        } elseif ($userType === 'mahasiswa') {
            $user = Auth::guard('mahasiswa')->user();
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid user type']);
        }

        if ($user->profile_photo && Storage::exists('public/profile_photos/' . $user->profile_photo)) {
            Storage::delete('public/profile_photos/' . $user->profile_photo);
            $user->update(['profile_photo' => null]);
            return response()->json(['success' => true, 'message' => 'Foto profil berhasil dihapus']);
        }

        return response()->json(['success' => false, 'message' => 'Foto profil tidak ditemukan']);
    }
}