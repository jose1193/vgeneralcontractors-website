# Retell AI API Routes Summary

## **Base URL**: `https://vgeneralcontractors.com/api/retell`

## **Authentication**

-   All endpoints require `api_key` in the request body
-   API Key: `v3KQ7bHcP8fLTjGxE5mRnZ2sAyXu6pDwY9NVtJW4qrMzF`

---

## **1. Create Lead/Appointment**

**POST** `/leads`

```json
{
    "first_name": "Juan",
    "last_name": "Pérez",
    "phone": "3466920757",
    "email": "juan@example.com",
    "address": "123 Main St",
    "city": "Houston",
    "state": "TX",
    "zipcode": "77019",
    "country": "USA",
    "insurance_property": "yes",
    "intent_to_claim": "yes",
    "notes": "Customer notes",
    "damage_detail": "Roof damage details",
    "sms_consent": true,
    "lead_source": "Retell AI",
    "inspection_date": "2025-01-15",
    "inspection_time": "10:00",
    "api_key": "v3KQ7bHcP8fLTjGxE5mRnZ2sAyXu6pDwY9NVtJW4qrMzF"
}
```

---

## **2. Get Calendar Availability**

**POST** `/appointments/availability`

```json
{
    "start_date": "2025-01-15",
    "end_date": "2025-01-20",
    "api_key": "v3KQ7bHcP8fLTjGxE5mRnZ2sAyXu6pDwY9NVtJW4qrMzF"
}
```

---

## **3. Search Client Appointments**

**POST** `/appointments/client`

```json
{
    "phone": "3466920757",
    "api_key": "v3KQ7bHcP8fLTjGxE5mRnZ2sAyXu6pDwY9NVtJW4qrMzF"
}
```

---

## **4. Get Specific Appointment**

**POST** `/appointments/get`

```json
{
    "uuid": "906548b9-cac3-484f-a97d-c950f0117b21",
    "api_key": "v3KQ7bHcP8fLTjGxE5mRnZ2sAyXu6pDwY9NVtJW4qrMzF"
}
```

---

## **5. Update Appointment** ✅ FIXED

**POST** `/appointments/update` OR **PATCH** `/appointments/update`

```json
{
    "uuid": "906548b9-cac3-484f-a97d-c950f0117b21",
    "first_name": "Jose",
    "last_name": "Gonzalez",
    "phone": "3466920757",
    "api_key": "v3KQ7bHcP8fLTjGxE5mRnZ2sAyXu6pDwY9NVtJW4qrMzF"
}
```

---

## **6. Delete/Decline Appointment** ✅ FIXED

**POST** `/appointments/delete`

```json
{
    "uuid": "906548b9-cac3-484f-a97d-c950f0117b21",
    "api_key": "v3KQ7bHcP8fLTjGxE5mRnZ2sAyXu6pDwY9NVtJW4qrMzF"
}
```

---

## **7. Reschedule Appointment**

**POST** `/appointments/reschedule`

```json
{
    "uuid": "906548b9-cac3-484f-a97d-c950f0117b21",
    "new_date": "2025-01-20",
    "new_time": "14:00",
    "api_key": "v3KQ7bHcP8fLTjGxE5mRnZ2sAyXu6pDwY9NVtJW4qrMzF"
}
```

---

## **8. Update Appointment Status**

**POST** `/appointments/status` OR **PATCH** `/appointments/status`

```json
{
    "uuid": "906548b9-cac3-484f-a97d-c950f0117b21",
    "inspection_status": "Confirmed",
    "api_key": "v3KQ7bHcP8fLTjGxE5mRnZ2sAyXu6pDwY9NVtJW4qrMzF"
}
```

---

## **Changes Made for Retell AI Compatibility:**

### ✅ **Fixed Issues:**

1. **JSON Parsing**: Added `parseRequestData()` to handle Retell AI's nested JSON structure
2. **Phone Search**: Added automatic phone formatting for search compatibility
3. **HTTP Methods**: Added POST routes for all endpoints (Retell AI prefers POST)
4. **Validation**: Made update validations more flexible and forgiving

### 🔧 **Available Methods:**

-   **POST**: All endpoints support POST for maximum Retell AI compatibility
-   **PATCH**: Update and status endpoints also support PATCH for standard REST
-   **DELETE**: Removed (Retell AI couldn't use it)

### 📱 **Phone Number Handling:**

-   **Input**: Accepts any format (`3466920757`, `(346) 692-0757`, `346-692-0757`)
-   **Storage**: Automatically formatted to `(346) 692-0757`
-   **Search**: Works with both formatted and unformatted numbers

### 🔍 **Debugging:**

-   Enhanced logging for troubleshooting
-   Clear error messages with debug information
-   Validation details in error responses
