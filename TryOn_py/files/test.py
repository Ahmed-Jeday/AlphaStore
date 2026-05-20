from dotenv import load_dotenv
import os
import replicate

load_dotenv()
client = replicate.Client(api_token=os.getenv("REPLICATE_API_TOKEN"))
print(client.models.get("cuuupid/idm-vton").id)