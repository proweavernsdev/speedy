// users.d.ts
export interface User {
  id: string;
  full_name: string;
  username: string;
  email: string;
  password: string;
  type: "customer" | "driver";
}



export const users: User[];
