namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            // Tìm xem user đã có google_id này chưa
            $existingUser = User::where('google_id', $user->id)->first();

            if($existingUser){
                Auth::login($existingUser);
                return redirect()->intended('/'); // Chuyển về trang chủ
            } else {
                // Nếu chưa có, tạo user mới
                $newUser = User::updateOrCreate(['email' => $user->email], [
                    'name' => $user->name,
                    'google_id'=> $user->id,
                    // Tạo mật khẩu ngẫu nhiên vì login qua Google không cần pass
                    'password' => Hash::make(Str::random(24)) 
                ]);

                Auth::login($newUser);
                return redirect()->intended('/');
            }
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Lỗi đăng nhập: ' . $e->getMessage());
        }
    }
}