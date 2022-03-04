Matthew Randle
JOB APPLICATIONS / FINANCE SYSTEM (also includes /webhooks folder)

Employers can see the jobs they have posted that are still open
Employers can see the amount of applications a job has
Employers can see a list of the proposals that freelancers have made to their job, includes upfront and total payment required
Employers can see their active jobs (jobs that have a freelancer working)
Employers can see their finished jobs (jobs that have been paid for)
Employers can pay the freelancer a required upfront amount before a job is started
Employers can pay the freelancer the total cost once the job has completed (total - upfront)
If the employer has accepted an application but hasn't paid the required upfront cost, the active job list will tell them too
When an employer makes a payment a request is made using Stripe Checkout and the user is redirected to the payment success page
When a payment is completed on stripes end, stripe sends a request to the webhooks folder, where the job is then marked as paid
If a payment fails the job will not be marked as paid
The freelancer has a balance attached to their account which represents the amount they have been paid for any work they have done