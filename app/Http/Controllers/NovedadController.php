<?phpnamespace App\Http\Controllers;use App\Novedad;use Illuminate\Http\Request;use Illuminate\Support\Facades\Hash;class NovedadController extends Controller {    /**     * Display a listing of the resource.     *     * @return \Illuminate\Http\Response     */    public function __construct() {        $this->middleware('auth');    }    public function index() {        return view('novedad.create');    }    /**     * Show the form for creating a new resource.     *     * @return \Illuminate\Http\Response     */    public function create(Request $data) {        $descripcion = $data->input('descripcion');        if ($descripcion) {            $novedad = new novedad;            $novedad->descripcion = $descripcion;            $novedad->fecha = date('Y-m-d');            $novedad->save();        }        return redirect('/home');    }}