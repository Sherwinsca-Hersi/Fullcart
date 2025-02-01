
    async function fetchLocationData() {
        const pincodeInput = document.getElementById("pincode");
        const pincode = pincodeInput.value.trim();
        const suggestionBox = document.getElementById("suggestion_box");

        document.getElementById("city").value = "";
        document.getElementById("state").value = "";
        document.getElementById("country").value = "";
        suggestionBox.textContent = "";

        if (pincode.length === 6 && /^\d{6}$/.test(pincode)) {
            try {
                const response = await fetch(`https://api.postalpincode.in/pincode/${pincode}`);
                const data = await response.json();

                if (data && data[0].Status === "Success" && data[0].PostOffice.length > 0) {
                    const location = data[0].PostOffice[0];
                    document.getElementById("city").value = location.District;
                    document.getElementById("state").value = location.State;
                    document.getElementById("country").value = location.Country;
                    suggestionBox.textContent = "";
                } else {
                    suggestionBox.textContent = "Location data not found for this pincode. Please check the entered pincode.";
                }
            } catch (error) {
                console.error("Error fetching location data:", error);
                suggestionBox.textContent = "An error occurred while fetching location data. Please try again.";
            }
        } else if (pincode.length < 6) {
            suggestionBox.textContent = "";
        } else {
            suggestionBox.textContent = "Please enter a valid 6-digit pincode.";
            pincodeInput.value = pincode.slice(0, 6);
        }
    }

