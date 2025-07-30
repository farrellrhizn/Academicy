<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Golongan;
use App\Services\ImageService;

class ProfileController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

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

        $newPhotoUrl = null;

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            try {
                // Delete old photo if exists
                if ($dosen->profile_photo) {
                    $this->imageService->deleteProfilePhoto($dosen->profile_photo);
                }

                $file = $request->file('profile_photo');
                
                // Validate file type
                if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])) {
                    throw new \Exception('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                }
                
                $fileName = 'dosen_' . $dosen->NIP . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Resize and save the image
                $savedFileName = $this->imageService->resizeProfilePhoto($file, $fileName, 200, 200);
                $data['profile_photo'] = $savedFileName;
                $newPhotoUrl = asset('storage/profile_photos/' . $savedFileName);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload foto: ' . $e->getMessage()
                ]);
            }
        }

        $dosen->update($data);

        // Return JSON response if AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui!',
                'profile_photo_url' => $newPhotoUrl,
                'user_name' => $dosen->Nama
            ]);
        }

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

        $newPhotoUrl = null;

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            try {
                // Delete old photo if exists
                if ($mahasiswa->profile_photo) {
                    $this->imageService->deleteProfilePhoto($mahasiswa->profile_photo);
                }

                $file = $request->file('profile_photo');
                
                // Validate file type
                if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])) {
                    throw new \Exception('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                }
                
                $fileName = 'mahasiswa_' . $mahasiswa->NIM . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Resize and save the image
                $savedFileName = $this->imageService->resizeProfilePhoto($file, $fileName, 200, 200);
                $data['profile_photo'] = $savedFileName;
                $newPhotoUrl = asset('storage/profile_photos/' . $savedFileName);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload foto: ' . $e->getMessage()
                ]);
            }
        }

        $mahasiswa->update($data);

        // Return JSON response if AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui!',
                'profile_photo_url' => $newPhotoUrl,
                'user_name' => $mahasiswa->Nama
            ]);
        }

        return redirect()->back()->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Upload profile photo specifically
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_type' => 'required|in:dosen,mahasiswa'
        ]);

        $userType = $request->input('user_type');
        
        if ($userType === 'dosen') {
            $user = Auth::guard('dosen')->user();
            $prefix = 'dosen_' . $user->NIP;
        } elseif ($userType === 'mahasiswa') {
            $user = Auth::guard('mahasiswa')->user();
            $prefix = 'mahasiswa_' . $user->NIM;
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid user type']);
        }

        try {
            // Delete old photo if exists
            if ($user->profile_photo) {
                $this->imageService->deleteProfilePhoto($user->profile_photo);
            }

            $file = $request->file('profile_photo');
            
            // Validate file type
            if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])) {
                throw new \Exception('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
            }
            
            $fileName = $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Resize and save the image
            $savedFileName = $this->imageService->resizeProfilePhoto($file, $fileName, 200, 200);
            
            // Update user profile_photo
            $user->update(['profile_photo' => $savedFileName]);
            
            $photoUrl = asset('storage/profile_photos/' . $savedFileName);
            
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupload!',
                'profile_photo_url' => $photoUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto: ' . $e->getMessage()
            ]);
        }
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

        try {
            if ($user->profile_photo && $this->imageService->deleteProfilePhoto($user->profile_photo)) {
                $user->update(['profile_photo' => null]);
                return response()->json([
                    'success' => true, 
                    'message' => 'Foto profil berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false, 
                'message' => 'Foto profil tidak ditemukan atau gagal dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get current profile photo
     */
    public function getProfilePhoto(Request $request)
    {
        $userType = $request->input('user_type');
        
        if ($userType === 'dosen') {
            $user = Auth::guard('dosen')->user();
        } elseif ($userType === 'mahasiswa') {
            $user = Auth::guard('mahasiswa')->user();
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid user type']);
        }

        $photoUrl = null;
        if ($user->profile_photo && Storage::exists('public/profile_photos/' . $user->profile_photo)) {
            $photoUrl = asset('storage/profile_photos/' . $user->profile_photo);
        }

        return response()->json([
            'success' => true,
            'profile_photo_url' => $photoUrl,
            'has_photo' => !is_null($photoUrl)
        ]);
    }
}