namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact;

class ContactForm extends Component
{
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $message;
    public $type = 'contact'; // Default type
    public $success = false;

    protected $rules = [
        'firstName' => 'required|min:2',
        'lastName' => 'required|min:2',
        'email' => 'required|email',
        'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'message' => 'required|min:10',
    ];

    public function mount($type = null)
    {
        if ($type) {
            $this->type = $type;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        $this->validate();

        Contact::create([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'type' => $this->type
        ]);

        $this->reset(['firstName', 'lastName', 'email', 'phone', 'message']);
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
} 