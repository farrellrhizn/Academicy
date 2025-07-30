<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        try {
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
                    // Ensure directory exists
                    $this->ensureDirectoryExists();
                    
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
                    
                    Log::info('Profile photo uploaded successfully for dosen: ' . $dosen->NIP, [
                        'file_name' => $savedFileName,
                        'file_size' => $file->getSize()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to upload profile photo for dosen: ' . $dosen->NIP, [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal mengupload foto: ' . $e->getMessage()
                        ]);
                    }
                    
                    return redirect()->back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
                }
            }

            $dosen->update($data);
            
            Log::info('Profile updated successfully for dosen: ' . $dosen->NIP);

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
        } catch (\Exception $e) {
            Log::error('Error updating dosen profile: ' . $e->getMessage(), [
                'dosen_id' => Auth::guard('dosen')->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui profile: ' . $e->getMessage()
                ]);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profile.');
        }
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
        try {
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
                    // Ensure directory exists
                    $this->ensureDirectoryExists();
                    
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
                    
                    Log::info('Profile photo uploaded successfully for mahasiswa: ' . $mahasiswa->NIM, [
                        'file_name' => $savedFileName,
                        'file_size' => $file->getSize()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to upload profile photo for mahasiswa: ' . $mahasiswa->NIM, [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal mengupload foto: ' . $e->getMessage()
                        ]);
                    }
                    
                    return redirect()->back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
                }
            }

            $mahasiswa->update($data);
            
            Log::info('Profile updated successfully for mahasiswa: ' . $mahasiswa->NIM);

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
        } catch (\Exception $e) {
            Log::error('Error updating mahasiswa profile: ' . $e->getMessage(), [
                'mahasiswa_id' => Auth::guard('mahasiswa')->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui profile: ' . $e->getMessage()
                ]);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profile.');
        }
    }

    /**
     * Upload profile photo specifically
     */
    public function uploadPhoto(Request $request)
    {
        try {
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

            // Ensure directory exists
            $this->ensureDirectoryExists();

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
            
            Log::info('Profile photo uploaded via dedicated endpoint', [
                'user_type' => $userType,
                'user_id' => $user->id,
                'file_name' => $savedFileName
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupload!',
                'profile_photo_url' => $photoUrl
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading profile photo: ' . $e->getMessage(), [
                'user_type' => $request->input('user_type'),
                'trace' => $e->getTraceAsString()
            ]);
            
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
        try {
            $userType = $request->input('user_type');
            
            if ($userType === 'dosen') {
                $user = Auth::guard('dosen')->user();
            } elseif ($userType === 'mahasiswa') {
                $user = Auth::guard('mahasiswa')->user();
            } else {
                return response()->json(['success' => false, 'message' => 'Invalid user type']);
            }

            if ($user->profile_photo && $this->imageService->deleteProfilePhoto($user->profile_photo)) {
                $user->update(['profile_photo' => null]);
                
                Log::info('Profile photo deleted successfully', [
                    'user_type' => $userType,
                    'user_id' => $user->id
                ]);
                
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
            Log::error('Error deleting profile photo: ' . $e->getMessage(), [
                'user_type' => $request->input('user_type'),
                'trace' => $e->getTraceAsString()
            ]);
            
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
        try {
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
        } catch (\Exception $e) {
            Log::error('Error getting profile photo: ' . $e->getMessage(), [
                'user_type' => $request->input('user_type'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Ensure the profile photos directory exists
     */
    private function ensureDirectoryExists()
    {
        $directory = storage_path('app/public/profile_photos');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
            Log::info('Created profile_photos directory: ' . $directory);
        }
        
        // Also ensure proper permissions
        if (!is_writable($directory)) {
            chmod($directory, 0755);
            Log::info('Fixed permissions for profile_photos directory: ' . $directory);
        }
    }
}