

## About The Application

The un-nanmed special education helper's intention is to create a useful resource for parents
struggling to make sense of the IEP process, and what laws to reference in their state if 
things go wrong.

## Features

- A scheduler that fetches a states special education laws.
  - It also has a command to fetch the [IDEA](https://sites.ed.gov/idea/) laws in the same manner.
  - In the lack of proper data APIs for this data, I retrieve it by scraping directly from their website.
- Search laws from selected states.
  - Search across all states to compare, or filter by your own state to quickly find exactly the law you need.
- Upload documentation. 
  - The ability to upload documents related to a child's education and tag reminder dates for acting on a document.
  - This uses Amazon S3 for storage.
- Team contribution.
  - You may invite people to the site as a team member, with limited privileges, to help or view what documents you have uploaded.